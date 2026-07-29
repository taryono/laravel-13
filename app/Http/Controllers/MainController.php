<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
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
    protected $model;
    public function __construct($model){
        $this->model = $model;
    }

    public function getForm($model){
        return (new \Lib\FormLib($model))->getFields()->getForms();
    }
}
