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
use Intervention\Image\Image;

class FileUploader {

    /**
     *
     *@param Illuminate\Support\Facades\File $file
     *@param string $file_directory
     *
     *@return array
     */
    public static function upload($file, $file_directory = "uploads",$storage = "file"){

        $storage = env('FILE_STORAGE', "file");

        // $file = $request->file("file");
        $ext = $file->getClientOriginalExtension()?: "txt";
        $mime_type = $file->getMimeType()?:"plain/text";

        $file_size = $file->getSize();

        $path_directory = $file_directory;

        if (!File::exists($path_directory)){
            File::makeDirectory($path_directory, $mode = 0777, true, true);
        }

        $filename = create_filename($ext);

        switch (Str::lower($storage)) {
            case 'file':

                $file->move($path_directory, $filename);
                return [
                    "path" => Str::lower($file_directory),
                    "directory" => Str::lower(URL::to($path_directory)),
                    "filename" => Str::lower($filename),
                    "source" => Str::lower($storage),
                    "type" => $mime_type,
                    "size" => $file_size

                ];

                break;

            case 'azure':
                $new_image_filename = $filename;
                $file->move($path_directory, $filename);


                $client = new AzureStorage(env('BLOB_STORAGE_URL'),env('BLOB_ACCOUNT_NAME'),env('BLOB_ACCESS_KEY'));

                $container= env('BLOB_CONTAINER');
                $orig_container = env('BLOB_ORIG_CONTAINER');
                $directory = env('BLOB_STORAGE_URL')."/".env('BLOB_CONTAINER');

                $new_file_directory = "{$directory}/".str_replace("uploads/", "", $path_directory);
                $new_file_path = str_replace("uploads/", "", $path_directory);

                // $client->putBlob($orig_container, "{$new_file_path}/{$filename}", "{$path_directory}/{$filename}",[],null,['x-ms-blob-content-type'     => $mime_type]);
                $client->putBlob($container, "{$new_file_path}/{$filename}", "{$path_directory}/{$filename}",[],null,['x-ms-blob-content-type'     => $mime_type]);


                if (File::exists("{$path_directory}/{$filename}")){
                    File::delete("{$path_directory}/{$filename}");
                }

                return [
                    "path" => Str::lower($new_file_path),
                    "directory" => Str::lower($new_file_directory),
                    "filename" => Str::lower($new_image_filename),
                    "source" => Str::lower($storage),
                    "type" => $mime_type,
                    "size" => $file_size


                ];
                break;

            case 'aws':

                $new_filename = $filename;
                $file->move($path_directory, $filename);
                $prefix_storage = env('AWS_S3_CONTAINER_PREFIX',"");
                $new_image_directory = str_replace("uploads/", "{$prefix_storage}", $path_directory);
                $new_file_path = str_replace("uploads/", "{$prefix_storage}", $path_directory);
                $directory = env('AWS_S3_CONTAINER')."/".$new_file_path;
                $s3 = Storage::disk('s3');
                $s3->put("{$new_file_path}/{$filename}",file_get_contents("{$path_directory}/{$filename}"));

                if (File::exists("{$path_directory}/{$filename}")){
                    File::delete("{$path_directory}/{$filename}");
                }

                return [
                    "path" => Str::lower($new_file_path),
                    "directory" => Str::lower($directory),
                    "filename" => Str::lower($new_filename),
                    "source" => Str::lower($storage),
                    "type" => $mime_type,
                    "size" => $file_size
                ];

                break;

            default:
                return array();
                break;
        }
    }
}
