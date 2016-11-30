<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Auth;
use App\Elan;
use App\Photo;
class IstekController extends Controller
{
  //<================= METHHOD FOR SHOW PAGE ================>
    public function show()
    {
      return view('pages.istek_add');
    }

  //<================= METHHOD FOR ISTEK_ADD PAGE ================>
    public function istek_add(Request $req)
    {
     $this->validate($req, [
     'title' => 'required',
         'about' => 'required',
         'location' => 'required',
         'lat' => 'required',
         'lng' => 'required',
         'name' => 'required',
         'phone' => 'required',
         'email' => 'required',
         // 'image' => 'required',
         'nov' => 'required',
    ]);


   
     $files = $req->file('image');
     $pic_name = array();
     foreach ($files as $file) {
       $filetype=$file->getClientOriginalExtension();
       // echo "$filetype";
       if($filetype=='jpg' || $filetype=='jpeg' || $filetype=='png'){
        array_push($pic_name, $filetype);
       }
       else{
        Session::flash('imageerror' , "Xahiş olunur şəkili düzgun yükləyəsiniz.");
          return redirect('/istek-add');
       }
     }
     // $filetype=='jpg' || $filetype=='jpeg' || $filetype=='png'
       // if (in_array(needle, haystack)) {
         // $filename=time().'.'.$filetype;
         // $req->file('image')->move(public_path('image'),$filename);
         Session::flash('istekadded' , "İstəyiniz uğurla  əlavə olundu və yoxlamadan keçəndən sonra dərc olunacaq.");
         $data = [
               'type_id'=>'2',
               'title'=>$req->title,
               'about'=>$req->about,
               'location'=>$req->location,
               'lat'=>$req->lat,
               'lng'=>$req->lng,
               'name'=>$req->name,
               'phone'=>'+994'.$req->operator.$req->phone,
               'email'=>$req->email,
               // 'image'=>$filename,
               'org'=>$req->org,
               'nov'=>$req->nov,
               'deadline'=>$req->date
             ];
          $insert_pic_id = Auth::user()->elanlar()->create($data)->shekiller();
          $files = $req->file('image');

          foreach ($files as $file) {
            $file_name =  time().$file->getClientOriginalName();
            $file->move(public_path('image'),$file_name);
            $data = new Photo;
            $data->imageName = $file_name;
            $insert_pic_id->save($data);
      }

            return redirect('/istek-add');
      //  }
      // else
      //   {
      //      Session::flash('imageerror' , "Xahiş olunur şəkili düzgun yükləyəsiniz.");
      //     return redirect('/istek-add');
      //    }
  }


  //<================= METHHOD FOR ISTEK_EDIT ================>
  public function istek_edit($id)
  {
    $istek_edit = Elan::find($id);
    return view('pages.istek_edit',compact('istek_edit'));
  }

  //<================= METHHOD FOR SAVING IMG WITH AJAX ================>

   public function only_pic(Request $req)////////yeni func
        {

          if ($req->ajax()) {
            $fileName = $req->file->getClientOriginalName();
            $file = $_FILES['file'];
            $istek_id = $_POST['istek_id']; 
            $file['istek_id'] = $istek_id;

            $file_name =date('ygmis').'.'.$fileName;
      
            $req->file->move(public_path('image'), $file_name);
            $sekil = Elan::find($istek_id);
            $hamsi = $sekil->shekiller();
            $data = new Photo;
            $data->imageName = $file_name;          
            $hamsi->save($data);

          }        

        }

  //<============ METHHOD FOR DELETING X PRESSED IMGS FROM EDITING=======>

        public function delete_edited_pics($pics) {
          if(!$pics) return false;
            foreach ($pics as $pic=>$status) {
              if(file_exists('image/'.$pic)){
                if($status == 0) {
                  unlink('image/'.$pic);
                  Photo::where('imageName', $pic)->delete();
                }
              }
            }
        }

  //<============ METHHOD FOR DELETING X PRESSED IMGS WITH AJAX=======>



  //<================= METHHOD FOR ISTEK_EDIT ================>
  public function istek_update(Request $req,$id)
  {
        $this->validate($req, [
            'title' => 'required',
            'about' => 'required',
            'location' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'nov' => 'required',
    ]);

        $this->delete_edited_pics($req->input('picsArray'));
       // if ($req->image == '') {
       //    $image = Elan::find($id);
       //    $photoname = $image->image;
       //  }
       //  else{
       //  // image upload
       //  $phototype=$req->file('image')->getClientOriginalExtension();
       //  $photoname=time().'.'.$phototype;
       //  $req->file('image')->move(public_path('image'),$photoname);

       //  }
       Session::flash('istek_edited' , "İstəyiniz uğurla dəyişdirildi və yoxlamadan keçəndən sonra dərc olunacaq.");
       $istek_update = Elan::find($id);
       $istek_update->title = $req->title;
       $istek_update->location = $req->location;
       $istek_update->lat = $req->lat;
       $istek_update->lng = $req->lng;
       $istek_update->about = $req->about;
       // $istek_update->image = $photoname;
       $istek_update->name = $req->name;
       $istek_update->email = $req->email;
       $istek_update->org = $req->org;
       $istek_update->nov = $req->nov;
       $istek_update->deadline = $req->date;
       $istek_update->phone = $req->phone;
       $istek_update->status = 0;
       $istek_update->update();
       return redirect("/istek-edit/$istek_update->id");
  }


  //<================= METHHOD FOR ISTEK_EDIT ================>
   public function istek_delete($id)
   {
     $istek_delete=Elan::find($id);
     unlink('image/'.$istek_delete->image);
     $istek_delete->delete();
     return back();
   }
}
