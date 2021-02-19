<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Magazine;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class MagazineController
 * @package App\Http\Controllers\api
 */
class MagazineController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request){

    $request->validate([
            'name' => 'required|string',
            'img' => 'required|string',
            'date' => 'required|string|date'

        ]
    );


        try {
            if (!$request->accepts('application/json')) {
                return response()->json(['fail'=>'wrong content type'],400);
            }


            $image = $request->input('img');  // your base64 encoded

            $size = $this->getBase64ImageSize($image);

            if($size>2){

                return response()->json(['fail'=>'big size content'],400);
            }


            $decoded_file = base64_decode($image); // decode the file

            $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type

            $extension = $this->mime2ext($mime_type); // extract extension from mime type

            if($extension!=="png"&&$extension!=="jpg"){

                return response()->json(['fail'=>'wrong image  type'],400);
            }

            $image = str_replace('data:image/'.$extension.';base64,', '', $image);

            $image = str_replace(' ', '+', $image);

            $filename = str::random(10).'.'.$extension;

            $file =   Storage::disk('local')->put('image/'.$filename, base64_decode($image));


            $magazine = Magazine::create([
        'date' => $request->input('date'),
        'name' => $request->input('name'),
        'short_description' => $request->input('short_description'),
        'img' => '/storage/' . $file,

    ]);
      }catch (\Exception $ex){

    return response()->json(['fail'=>'add magazine content'],400);
    }

    return response()->json(['add'=>'add magazine content'],201);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id){

        $request->validate([
                'name'=>'required|string',
                'img'=>'required|string',
                'date' =>'required|string|date'

            ]
        );

        $magazine=Magazine::find($id);
        if (!$request->accepts('application/json')) {
            return response()->json(['fail'=>'wrong content type'],400);
        }
        try {
        if(file_exists(public_path($magazine->img))){
            app(Filesystem::class)->delete(public_path($magazine->img));
        }


            $image = $request->input('img');  // your base64 encoded

            $size = $this->getBase64ImageSize($image);

            if($size>2){

                return response()->json(['fail'=>'big size content'],400);
            }

            $decoded_file = base64_decode($image); // decode the file

            $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type

            $extension = $this->mime2ext($mime_type); // extract extension from mime type

            if($extension!=="png"&&$extension!=="jpg"){

                return response()->json(['fail'=>'wrong type image'],400);
            }


            $image = str_replace('data:image/'.$extension.';base64,', '', $image);

            $image = str_replace(' ', '+', $image);

            $filename = str::random(10).'.'.$extension;

            $file =   Storage::disk('local')->put('image/'.$filename, base64_decode($image));



            $magazine->update([
            'date'=>$request->input('date'),
            'name'=>$request->input('name'),
            'short_description'=>$request->input('short_description'),
            'img'=>'/storage/'.$file
        ]);
    }catch (\Exception $ex){

       return response()->json(['fail'=>'Update magazine content'],200);
      }

        return response()->json(['update'=>'Update magazine content'],200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id){

        $magazine=Magazine::find($id);


        if(file_exists(public_path($magazine->img))){
            app(Filesystem::class)->delete(public_path($magazine->img));
        }

        $magazine->delete();


        return response()->json(['delete' =>'Delete magazine'],204);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request){

        $page= $request->input('page');

        $perPage= $request->input('perPage');
        if (!$request->accepts('application/json')) {
            return response()->json(['fail'=>'wrong content type '],400);
        }

        $magazine=Magazine::all();

        return response()->json($magazine,200);


    }

    /*
    to take mime type as a parameter and return the equivalent extension
    */
    /**
     * @param $mime
     * @return false|int|string
     */
    public function mime2ext($mime){
        $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp",
        "image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp",
        "image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp",
        "application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg",
        "image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],
        "wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],
        "ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg",
        "video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],
        "kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],
        "rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application",
        "application\/x-jar"],"zip":["application\/x-zip","application\/zip",
        "application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],
        "7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],
        "svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],
        "mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],
        "webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],
        "pdf":["application\/pdf","application\/octet-stream"],
        "pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],
        "ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office",
        "application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
        "xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],
        "xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel",
        "application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],
        "xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo",
        "video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],
        "log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],
        "wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],
        "tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop",
        "image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],
        "mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar",
        "application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40",
        "application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],
        "cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary",
        "application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],
        "ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],
        "wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],
        "dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php",
        "application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],
        "swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],
        "mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],
        "rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],
        "jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],
        "eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],
        "p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],
        "p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
        $all_mimes = json_decode($all_mimes,true);
        foreach ($all_mimes as $key => $value) {
            if(array_search($mime,$value) !== false) return $key;
        }
        return false;
    }

    /**
     * @param $base64Image
     * @return \Exception|float|int
     */
    public function getBase64ImageSize($base64Image)
    {
        try{
            $size_in_bytes = (int) (strlen(rtrim($base64Image, '=')) * 3 / 4);
            $size_in_kb    = $size_in_bytes / 1024;
            $size_in_mb    = $size_in_kb / 1024;

            return $size_in_mb;
        }
        catch(\Exception $e){
            return $e;
        }
    }


}
