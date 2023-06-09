<?php

namespace App\Http\Controllers;
use App\Models\Tintuc;
use App\Models\Theloai;
use App\Models\Loaitin;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TintucController extends Controller
{
    public function getDanhSachTinTuc()
    {   
        $tintuc = Tintuc::orderBy("id", "DESC")->paginate(10);
        return view("admin.tintuc.danhsach", compact("tintuc"));
    }

    public function getThemTinTuc()
    {
        $loaitin = Loaitin::all();
        $theloai = Theloai::all();  
        return view("admin.tintuc.them", compact("loaitin", "theloai"));
    }

    public function getLoaiTin($idtl)
    {
        $loaitin = LoaiTin::where("idTheLoai", $idtl)->get();
            foreach($loaitin as $lt)
            {   
                echo "<option value='".$lt->id."'>".$lt->Ten."</option>";
            }
    }

    public function postThemTinTuc(Request $req)
    {
        $val = $req->validate([
            "loaitin" => "required",
            "tieude" => "required|min:3|unique:tintuc,TieuDe",
            "tomtat" => "required",
            "noidung" => "required"
        ],[
            "loaitin.required" => "Bạn chưa chọn loại tin",
            "tieude.required" => "Bạn chưa nhập tiêu đề",
            "tieude.min" => "Tiêu đề ít nhất 3 ký tự",
            "tieude.unique" => "Tiêu đề đã tồn tại",
            "tomtat.required" => "Bạn chưa nhập tóm tắt",
            "noidung.required" => "Bạn chưa nhập nội dung"
        ]);
                            
        $tt = new tintuc();
        $tt ->TieuDe = $val["tieude"];
        $tt ->TieuDeKhongDau = changeTitle($val["tieude"]);
        $tt ->idLoaiTin = $val["loaitin"];
        $tt ->TomTat = $val["tomtat"];
        $tt ->NoiDung = $val["noidung"];
        $tt ->SoLuotXem = 0;
        if ($req->hasFile("hinh"))
        {
            $file = $req->file("hinh");
            $ext = $file->getClientOriginalExtension();
            if ($ext != "jpg" && $ext != "png" && $ext != "jpeg")
            {
                return redirect("admin/tintuc/them")->with("loi", "Bạn chỉ được chọn file
                hình có đuôi: .jpg, .png, .jpeg");
            }
            $ten = $file->getClientOriginalName();
            $name = Str::random(4);
            $name = $name."_".$ten;
            while(file_exists("images".$name))
            {
                $name = Str::random(4);
                $name = $name."_".$ten;
            }
            $file->move("images", $name);
            $tt->Hinh = $name;
        }
        else
        {
            $tt->Hinh = "";
        }
        $tt->save();
        return redirect("admin/tintuc/them")->with("thongbao", "Thêm tin tức thành công");
    }

    public function getSuaTinTuc($id)
    {
        $loaitin = Loaitin::all();
        $theloai = Theloai::all();
        $tintuc = Tintuc::find($id);
        return view("admin.tintuc.sua", compact("loaitin", "theloai", "tintuc"));
    }

    public function postSuaTinTuc(Request $req, $id)
    {
        $val = $req->validate([
            "loaitin" => "required",
            "tieude" => "required|min:3",
            "tomtat" => "required",
            "noidung" => "required"
        ],[
            "loaitin.required" => "Bạn chưa chọn loại tin",
            "tieude.required" => "Bạn chưa nhập tiêu đề",
            "tieude.min" => "Tiêu đề phải có ít nhất 3 ký tự",
            "tomtat.required" => "Bạn chưa nhập tóm tắt",
            "tomtat.required" => "Bạn chưa nhập nội dung"
        ]);
        $tt = Tintuc::find($id);
        $tt->Tieude = $val["tieude"];
        $tt->TieuDeKhongDau = changeTitle($val["tieude"]);
        $tt->idLoaitin = $val["loaitin"];
        $tt->TomTat = $val["tomtat"];
        $tt->NoiDung = $val["noidung"];
        $tt->SoLoutXem = 0;
        if ($req->hasFile("hinh"))
        {
            $file = $req->file("hinh");
            $ext = $file->getClientOriginalExtension();
            if ($ext != "jpg" && $ext != "png" && $ext != "jpeg")
            {
                return redirect("admin/tintuc/them")->with("loi", "Bạn chỉ được chọn file
                hình có đuôi: .jpg, .png, .jpeg");
            }
            $ten = $file->getClientOriginalName();
            $name = Str::random(4);
            $name = $name."_".$ten;
            while(file_exists("images".$name))
            {
                $name = Str::random(4);
                $name = $name."_".$ten;
            }
            $file->move("images", $name);
            $tt->Hinh = $name;
        }
        else
        {
            $tt->Hinh = "";
        }
        $tt->save();
        return redirect("admin/tintuc/sua".$id)->with("thongbao", "Sửa tin tức thành công");
    }
    
    public function getXoaTinTuc($id)
    {
        $tt = TinTuc::find($id);
        if(count($tt->comment)>0)
        {
            return redirect("admin/tintuc/danhsach")->with("thongbao", "Không xóa được tin tức này");
        }
        else
        {
            $tt->delete();
            return redirect("admin/tintuc/danhsach")->with("thongbao", "Xóa tin tức thành công");
        }

    }
}