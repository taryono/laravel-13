<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ProcessHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor; //  Import class PhpWord
use View;
use App\Rules\FileExtensions;
use App\Jobs\ConvertPdfToImageJob;
use App\Helpers\UploadHelper;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    protected $_user;
    protected $_model;
    protected $_controller_name;
    protected $_controller;
    protected $_title;
    protected $_is_ajax;
    protected $_is_modal;
    protected $_page;
    protected $_company;
    protected $_breadcrumb;
    public $fields     = [];
    public $with       = [];
    public $load       = [];
    public $headers    = [];
    public $attributes = [];
    public $custom_fields;
    public $defaultFoms;
    public $form_groups;
    protected $_limit = 1000;
    use ProcessHandler;

    public function __construct($model = null, $controller = null, $title = null, $breadcrumb = null, $custom_fields = [])
    {
        $this->middleware('auth');
        $this->_model           = $model;
        $this->_controller_name = $controller;
        $this->_controller      = Str::snake(str_replace("Controller", "", $controller));

        $this->_is_modal   = request()->input("is_modal");
        $this->_page       = request()->input("page");
        $this->_breadcrumb = $breadcrumb;
        $this->_is_ajax    = request()->input("is_ajax"); 
         
    }

    public function index()
    {
        if (request()->ajax()) {
            return view("{$this->_controller}::list");
        }

        return view("{$this->_controller}::index");
    }

    public function create()
    { 
        return view("{$this->_controller}::create", compact("field"));
    }

    public function edit($id)
    {
        //
        try {
            $data = $this->_model::findOrFail($id); 
            return view("{$this->_controller}::edit", compact('data'));
        } catch (\Exception $e) {
            return getError($e);
        }
    }

    public function setModel($model)
    {
        $this->_model = $model;
    }
    public function getModel()
    {
        return $this->_model;
    } 
 
    protected function _serialize($request, $model = null, $ignored = [])
    {
        $model      = $model ? $model : $this->_model;
        $exceptions = ['_method', '_token', 'id', 'password_confirmation', 'image', 'photo', 'file', 'logo', 'thumb'];

        if ($request instanceof Request) {
            $data = $request->except($exceptions);
        } else {
            $data = array_diff_key($request, $exceptions);
        }
        try {
            if ($ignored) {
                foreach ($ignored as $val) {
                    unset($data[$val]);
                }
            }

            if ($model) {
                $fields = $this->fields;
                if (count($fields) < 1) {
                    $fields = array_diff($model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable()), [
                        "created_at",
                        "updated_at",
                        "deleted_at",
                    ]);
                }

                $result = [];
                foreach ($data as $key => $val) {
                    if ($key == "password" && empty($val)) {
                        unset($data['password']);
                        continue;
                    }
                    if (in_array($key, $fields)) {
                        if ($key == "publish_date") {
                            if (!empty($val)) {
                                $result[$key] = dateFormat($data['publish_date']);
                            } else {
                                $result[$key] = dateFormat(date('Y-m-d H:i:s'));
                            }
                        } else {
                            $result[$key] = $val;
                        }
                    }
                }
                $data = $result;
                // $data = array_filter($data, fn($value) => $value !== null);
            }
        } catch (\Exception $e) {
            return getError($e);
        }
        return $data;
    }

    public function setTitle($title)
    {
        return $this->_title = $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function downloadFile($path)
    {
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"PROSES SERTIFIKASI LEMBAGA SERTIFIKASI PROFESI PENGADAAN INDONESIA.docx\"");

        return Response::download(Storage::get($path), 'PROSES SERTIFIKASI LEMBAGA SERTIFIKASI PROFESI PENGADAAN INDONESIA.docx', ['Content-Type' => 'application/octet-stream', 'Content-Disposition' => 'attachment']);
    }

    public function getFile()
    {

        if (! is_sftp_exists()) {
            return response()->json(['status' => 'error', 'message' => 'Connection is not exists'], 400);
        }
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"");

        try {
            $path = request()->input('path');

            if (! Storage::exists($path)) {
                return null;
            }

            $file = Storage::get($path);
            $mime = Storage::mimeType($path);

            return Response::make($file, 200)->header("Content-Type", $mime);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => say("Sepertinya ada kesalahan system")], 400);
        }
    }
  
    public function getWordToHtml()
    {

        $path = 'templates/ba.zip';
        $path = storage_path('app/' . $path);
        // Muat dokumen Word
        $phpWord = IOFactory::load($path);

        // Buat writer untuk HTML
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');

        // Menyimpan hasil konversi ke file HTML
        $outputFile = storage_path('app/templates/ba.html');

        $htmlWriter->save($outputFile);

        // Atau jika ingin langsung menampilkan HTML ke browser:
        ob_start();
        $htmlWriter->save("php://output");
        $htmlContent = ob_get_clean();
        echo $htmlContent;
    }
 
}
