<?php

namespace App\Laravel\Services;

/*
*
* Models used for this class
*/

/*
*
* Classes used for this class
*/
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class ImageUploader {

    /**
     *
     *@param Illuminate\Support\Facades\File $file
     *@param string $image_directory
     *
     *@return array
     */
    public static function upload($file, $image_directory = "uploads", $resized_size = 1024, $thumbnail_size = 320){

        $storage = env('IMAGE_STORAGE', "file");

        list($width, $height) = getimagesize($file);
        $file_type = $file->getMimeType();
        $file_size = $file->getSize();

        switch (Str::lower($storage)) {
            case 'file':
                // $file = $request->file("file");
                $ext = $file->getClientOriginalExtension();
                $thumbnail = ['height' => 250, 'width' => 250];
                $path_directory = $image_directory;
                $resized_directory = $image_directory."/resized";
                $thumb_directory = $image_directory."/thumbnails";
                $blur_directory = $image_directory."/blur";
                if (!File::exists($path_directory)){
                    File::makeDirectory($path_directory, $mode = 0777, true, true);
                }

                if (!File::exists($resized_directory)){
                    File::makeDirectory($resized_directory, $mode = 0777, true, true);
                }

                if (!File::exists($thumb_directory)){
                    File::makeDirectory($thumb_directory, $mode = 0777, true, true);
                }
                if(!File::exists($blur_directory)){
                    File::makeDirectory($blur_directory, $mode = 0777, true, true);

                }

                $filename = create_filename($ext);

                $file->move($path_directory, $filename);
                if($width >= 1024){
                    //if greater than or equalt to 1024 width
                    Image::make("{$path_directory}/{$filename}")->interlace()->widen(1024)->save("{$resized_directory}/{$filename}",95);
                    Image::make("{$path_directory}/{$filename}")->interlace()->widen(320)->save("{$thumb_directory}/{$filename}",95);
                    Image::make("{$path_directory}/{$filename}")->interlace()->widen(220)->save("{$blur_directory}/{$filename}",30);

                }else{
                    Image::make("{$path_directory}/{$filename}")->interlace()->widen($width)->save("{$resized_directory}/{$filename}",95);
                    Image::make("{$path_directory}/{$filename}")->interlace()->widen(220)->save("{$blur_directory}/{$filename}",30);

                    if($width >= 320){
                        //if greater than or equalt to 320 width
                        Image::make("{$path_directory}/{$filename}")->interlace()->widen(320)->save("{$thumb_directory}/{$filename}",95);
                    }else{
                        Image::make("{$path_directory}/{$filename}")->interlace()->widen($width)->save("{$thumb_directory}/{$filename}",95);
                    }

                }

                return [
                    "path" => $image_directory,
                    "directory" => URL::to($path_directory),
                    "filename" => $filename ,
                    "width" => $width,
                    "height" => $height,
                    "source" => $storage,
                    "type" => $file_type,
                    "size" => $file_size
                ];

                break;

            case 'azure':
                // $file = $request->file('file');
                $ext = $file->getClientOriginalExtension();
                $mime_type = $file->getMimeType();
                $thumbnail = ['height' => 250, 'width' => 250];

                $path_directory = $image_directory;
                $resized_directory = $image_directory."/resized";
                $thumb_directory = $image_directory."/thumbnails";

                if (!File::exists($path_directory)){
                    File::makeDirectory($path_directory, $mode = 0777, true, true);
                }

                if (!File::exists($resized_directory)){
                    File::makeDirectory($resized_directory, $mode = 0777, true, true);
                }

                if (!File::exists($thumb_directory)){
                    File::makeDirectory($thumb_directory, $mode = 0777, true, true);
                }

                $filename = create_filename($ext);
                $new_image_filename = $filename;
                $file->move($path_directory, $filename);

                if($width >= 1024){
                    //if greater than or equalt to 1024 width
                    Image::make("{$path_directory}/{$filename}")->interlace()->widen(1024)->save("{$resized_directory}/{$filename}",95);
                    Image::make("{$path_directory}/{$filename}")->interlace()->widen(320)->save("{$thumb_directory}/{$filename}",95);

                }else{
                    Image::make("{$path_directory}/{$filename}")->interlace()->widen($width)->save("{$resized_directory}/{$filename}",95);

                    if($width >= 320){
                        //if greater than or equalt to 320 width
                        Image::make("{$path_directory}/{$filename}")->interlace()->widen(320)->save("{$thumb_directory}/{$filename}",95);
                    }else{
                        Image::make("{$path_directory}/{$filename}")->interlace()->widen($width)->save("{$thumb_directory}/{$filename}",95);
                    }

                }

                $client = new AzureStorage(env('BLOB_STORAGE_URL'),env('BLOB_ACCOUNT_NAME'),env('BLOB_ACCESS_KEY'));

                $container= env('BLOB_CONTAINER');
                $orig_container = env('BLOB_ORIG_CONTAINER');
                $directory = env('BLOB_STORAGE_URL')."/".env('BLOB_CONTAINER');

                // $new_image_directory = "{$directory}/{$path_directory}";
                // $new_image_path = "{$path_directory}";

                $new_image_directory = "{$directory}/".str_replace("uploads/", "", $path_directory);
                $new_image_path = str_replace("uploads/", "", $path_directory);

                // $client->putBlob($orig_container, "{$new_image_path}/{$filename}", "{$path_directory}/{$filename}",[],null,['x-ms-blob-content-type'     => $mime_type]);
                $client->putBlob($container, "{$new_image_path}/thumbnails/{$filename}", "{$path_directory}/thumbnails/{$filename}",[],null,['x-ms-blob-content-type'     => $mime_type]);
                $client->putBlob($container, "{$new_image_path}/resized/{$filename}", "{$path_directory}/resized/{$filename}",[],null,['x-ms-blob-content-type'     => $mime_type]);
                $client->putBlob($container, "{$new_image_path}/{$filename}", "{$path_directory}/{$filename}",[],null,['x-ms-blob-content-type'     => $mime_type]);

                if (File::exists("{$path_directory}/{$filename}")){
                    File::delete("{$path_directory}/{$filename}");
                }
                if (File::exists("{$path_directory}/thumbnails/{$filename}")){
                    File::delete("{$path_directory}/thumbnails/{$filename}");
                }
                if (File::exists("{$path_directory}/resized/{$filename}")){
                    File::delete("{$path_directory}/resized/{$filename}");
                }

                return [
                    "path" => $new_image_path,
                    "directory" => $new_image_directory,
                    "filename" => $new_image_filename,
                    "width" => $width,
                    "height" => $height,
                    "source" => $storage,
                    "type" => $file_type,
                    "size" => $file_size
                ];
                break;

            case 'aws':
                $thumbnail = ['height' => 250, 'width' => 250];
                $path_directory = $image_directory;
                $resized_directory = $image_directory."/resized";
                $thumb_directory = $image_directory."/thumbnails";

                if (!File::exists(public_path($path_directory))){
                    File::makeDirectory(public_path($path_directory), $mode = 0777, true, true);
                }

                if (!File::exists(public_path($resized_directory))){
                    File::makeDirectory(public_path($resized_directory), $mode = 0777, true, true);
                }

                if (!File::exists(public_path($thumb_directory))){
                    File::makeDirectory(public_path($thumb_directory), $mode = 0777, true, true);
                }

                $filename = create_filename("jpg");
                $new_image_filename = $filename;
                $file->move($path_directory, $filename);
                Image::make(("{$path_directory}/{$filename}"))->interlace()->widen($resized_size)->save(("{$resized_directory}/{$filename}"),100);
                Image::make(("{$path_directory}/{$filename}"))->interlace()->widen($thumbnail_size)->save(("{$thumb_directory}/{$filename}"),100);
                $prefix_storage = env('AWS_S3_CONTAINER_PREFIX',"");
                $new_image_directory = str_replace("uploads/", "{$prefix_storage}", $path_directory);
                $new_image_path = str_replace("uploads/", "{$prefix_storage}", $path_directory);
                $directory = env('AWS_S3_CONTAINER')."/".$new_image_path;
                $s3 = Storage::disk('s3');

                $s3->put("{$new_image_path}/{$filename}",file_get_contents("{$path_directory}/{$filename}"));
                $s3->put("{$new_image_path}/thumbnails/{$filename}", file_get_contents("{$path_directory}/thumbnails/{$filename}"));
                $s3->put("{$new_image_path}/resized/{$filename}", file_get_contents("{$path_directory}/resized/{$filename}"));

                if (File::exists("{$path_directory}/{$filename}")){
                    File::delete("{$path_directory}/{$filename}");
                }
                if (File::exists("{$path_directory}/thumbnails/{$filename}")){
                    File::delete("{$path_directory}/thumbnails/{$filename}");
                }
                if (File::exists("{$path_directory}/resized/{$filename}")){
                    File::delete("{$path_directory}/resized/{$filename}");
                }

                return [
                    "path" => $new_image_path,
                    "directory" => $directory,
                    "filename" => $new_image_filename,
                    "width" => $width,
                    "height" => $height,
                    "source" => $storage,
                    "type" => $file_type,
                    "size" => $file_size
                ];
                break;

            default:
                return array();
                break;
        }
    }

    public static function uploadAutoResize($file, $image_directory = "uploads", $submitted_height=512,$submitted_width=512,$resized_size = 1024, $thumbnail_size = 320){

        $storage = env('IMAGE_STORAGE', "file");

        list($width, $height) = getimagesize($file);
        $file_type = $file->getMimeType();
        $file_size = $file->getSize();
        $thumb_height = number_format($submitted_height * 0.25,0,0);
        $thumb_width = number_format($submitted_width * 0.25,0,0);

        $ext = $file->getClientOriginalExtension();
        $thumbnail = ['height' => 250, 'width' => 250];
        $path_directory = $image_directory;
        $resized_directory = $image_directory."/resized";
        $thumb_directory = $image_directory."/thumbnails";
        $blur_directory = $image_directory."/blur";
        if (!File::exists($path_directory)){
            File::makeDirectory($path_directory, $mode = 0777, true, true);
        }

        if (!File::exists($resized_directory)){
            File::makeDirectory($resized_directory, $mode = 0777, true, true);
        }

        if (!File::exists($thumb_directory)){
            File::makeDirectory($thumb_directory, $mode = 0777, true, true);
        }
        if(!File::exists($blur_directory)){
            File::makeDirectory($blur_directory, $mode = 0777, true, true);

        }

        $filename = create_filename($ext);

        switch (Str::lower($storage)) {
            case 'file':
                // $file = $request->file("file");

                $file->move($path_directory, $filename);

                $canvas = Image::canvas($submitted_width, $submitted_height);
                $created_image = Image::make("{$path_directory}/{$filename}")->fit($submitted_width, $submitted_height, function ($constraint) { $constraint->aspectRatio(); });
                $canvas->insert($created_image,'center');
                $canvas->save("{$resized_directory}/{$filename}",95);

                $thumb_canvas = Image::canvas($thumb_width, $thumb_height);
                $created_thumb_image = Image::make("{$path_directory}/{$filename}")->fit($thumb_width, $thumb_height, function ($constraint) { $constraint->aspectRatio(); });
                $thumb_canvas->insert($created_thumb_image,'center');
                $thumb_canvas->save("{$thumb_directory}/{$filename}",95);
                $thumb_canvas->save("{$blur_directory}/{$filename}",30);

                return [
                    "path" => $image_directory,
                    "directory" => URL::to($path_directory),
                    "filename" => $filename ,
                    "width" => $width,
                    "height" => $height,
                    "source" => $storage,
                    "type" => $file_type,
                    "size" => $file_size
                ];

                break;

            case 'azure':
                $new_image_filename = $filename;
                $file->move($path_directory, $filename);

                $canvas = Image::canvas($submitted_width, $submitted_height);
                $created_image = Image::make("{$path_directory}/{$filename}")->fit($submitted_width, $submitted_height, function ($constraint) { $constraint->aspectRatio(); });
                $canvas->insert($created_image,'center');
                $canvas->save("{$resized_directory}/{$filename}",95);

                $thumb_canvas = Image::canvas($thumb_width, $thumb_height);
                $created_thumb_image = Image::make("{$path_directory}/{$filename}")->fit($thumb_width, $thumb_height, function ($constraint) { $constraint->aspectRatio(); });
                $thumb_canvas->insert($created_thumb_image,'center');
                $thumb_canvas->save("{$thumb_directory}/{$filename}",95);
                $thumb_canvas->save("{$blur_directory}/{$filename}",30);

                $client = new AzureStorage(env('BLOB_STORAGE_URL'),env('BLOB_ACCOUNT_NAME'),env('BLOB_ACCESS_KEY'));

                $container= env('BLOB_CONTAINER');
                $orig_container = env('BLOB_ORIG_CONTAINER');
                $directory = env('BLOB_STORAGE_URL')."/".env('BLOB_CONTAINER');

                // $new_image_directory = "{$directory}/{$path_directory}";
                // $new_image_path = "{$path_directory}";

                $new_image_directory = "{$directory}/".str_replace("uploads/", "", $path_directory);
                $new_image_path = str_replace("uploads/", "", $path_directory);

                // $client->putBlob($orig_container, "{$new_image_path}/{$filename}", "{$path_directory}/{$filename}",[],null,['x-ms-blob-content-type'     => $mime_type]);
                $client->putBlob($container, "{$new_image_path}/thumbnails/{$filename}", "{$path_directory}/thumbnails/{$filename}",[],null,['x-ms-blob-content-type'     => $mime_type]);
                $client->putBlob($container, "{$new_image_path}/resized/{$filename}", "{$path_directory}/resized/{$filename}",[],null,['x-ms-blob-content-type'     => $mime_type]);
                $client->putBlob($container, "{$new_image_path}/{$filename}", "{$path_directory}/{$filename}",[],null,['x-ms-blob-content-type'     => $mime_type]);

                if (File::exists("{$path_directory}/{$filename}")){
                    File::delete("{$path_directory}/{$filename}");
                }
                if (File::exists("{$path_directory}/thumbnails/{$filename}")){
                    File::delete("{$path_directory}/thumbnails/{$filename}");
                }
                if (File::exists("{$path_directory}/resized/{$filename}")){
                    File::delete("{$path_directory}/resized/{$filename}");
                }

                return [
                    "path" => $new_image_path,
                    "directory" => $new_image_directory,
                    "filename" => $new_image_filename,
                    "width" => $width,
                    "height" => $height,
                    "source" => $storage,
                    "type" => $file_type,
                    "size" => $file_size
                ];
                break;

            case 'aws':

                $new_image_filename = $filename;
                $file->move($path_directory, $filename);

                $canvas = Image::canvas($submitted_width, $submitted_height);
                $created_image = Image::make("{$path_directory}/{$filename}")->fit($submitted_width, $submitted_height, function ($constraint) { $constraint->aspectRatio(); });
                $canvas->insert($created_image,'center');
                $canvas->save("{$resized_directory}/{$filename}",95);

                $thumb_canvas = Image::canvas($thumb_width, $thumb_height);
                $created_thumb_image = Image::make("{$path_directory}/{$filename}")->fit($thumb_width, $thumb_height, function ($constraint) { $constraint->aspectRatio(); });
                $thumb_canvas->insert($created_thumb_image,'center');
                $thumb_canvas->save("{$thumb_directory}/{$filename}",95);
                $thumb_canvas->save("{$blur_directory}/{$filename}",30);

                $prefix_storage = env('AWS_S3_CONTAINER_PREFIX',"");
                $new_image_directory = str_replace("uploads/", "{$prefix_storage}", $path_directory);
                $new_image_path = str_replace("uploads/", "{$prefix_storage}", $path_directory);
                $directory = env('AWS_S3_CONTAINER')."/".$new_image_path;
                $s3 = Storage::disk('s3');

                $s3->put("{$new_image_path}/{$filename}",file_get_contents("{$path_directory}/{$filename}"));
                $s3->put("{$new_image_path}/thumbnails/{$filename}", file_get_contents("{$path_directory}/thumbnails/{$filename}"));
                $s3->put("{$new_image_path}/resized/{$filename}", file_get_contents("{$path_directory}/resized/{$filename}"));

                if (File::exists("{$path_directory}/{$filename}")){
                    File::delete("{$path_directory}/{$filename}");
                }
                if (File::exists("{$path_directory}/thumbnails/{$filename}")){
                    File::delete("{$path_directory}/thumbnails/{$filename}");
                }
                if (File::exists("{$path_directory}/resized/{$filename}")){
                    File::delete("{$path_directory}/resized/{$filename}");
                }

                return [
                    "path" => $new_image_path,
                    "directory" => $directory,
                    "filename" => $new_image_filename,
                    "width" => $width,
                    "height" => $height,
                    "source" => $storage,
                    "type" => $file_type,
                    "size" => $file_size
                ];
                break;

            default:
                return array();
                break;
        }
    }
}
