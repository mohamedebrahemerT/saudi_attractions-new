<?php 


namespace App\Http\Controllers;
 
use App\ContactUs;
use App\Transformers\ContactUsExportTransformer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

 
class ListContactUsController extends Controller
 {
  protected $transformer;

  public function __construct(ContactUsExportTransformer $transformer)
  {
        $this->transformer = $transformer;
  }

   public function index()
   {
       $contact_us = ContactUs::all();
 
       return view('requests.contact_us.index')
          ->with('contact_us', $contact_us);
   }

   public function exportContactUs($type)
   {
       $data = ContactUs::with('user')->get()->toArray();
       $data = $this->transformer->transformCollection($data);
       return Excel::create('Contact Us', function($excel) use ($data) {
           $excel->sheet('mySheet', function($sheet) use ($data)
         {
             $sheet->fromArray($data);
          });
 
       })->download($type);
    }
 }
