@extends("admin.layouts.master")
@section("content")
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Tin Tức
                    <small>{{$tintuc->TieuDe}}</small>
                </h1>
            </div>
            <!-- /.col-lg-12 -->
            <div class="col-lg-7" style="padding-bottom:120px">
                @if(count($errors)>0)
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $err)
                            {{$err}}
                            <br>
                        @endforeach
                    </div>
                @else
                    @if(session('thongbao'))
                        <div class="alert alert-success">
                           {{session('thongbao')}}
                        </div>
                    @endif
                @endif
                <form action="{{route('sua_tintuc', $tintuc->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Thể Loại</label>
                        <select class="form-control" id="theloai">
                            @foreach($theloai as $tl)
                                <option @if($tintuc->Loaitin->Theloai->id == $tl->id) {{"selected"}} @endif value="{{$tl->id}}">{{$tl->Ten}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Loại Tin</label>
                        <select class="form-control" name="loaitin" id="loaitin">
                            @foreach($loaitin as $lt)
                                <option @if($tintuc->Loaitin->id == $lt->id) {{"selected"}} @endif value="{{$lt->id}}">{{$lt->Ten}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tiêu Đề</label>
                        <input value="{{$tintuc->Tieude}}" name="tieude" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Tóm Tắt</label>
                        <input value="{{$tintuc->Tomtat}}" name="tomtat" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Nội Dung</label>
                        <textarea name="noidung" class="form-control ckeditor" rows="5">{{$tintuc->Noidung}}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Hình Ảnh</label>
                        <img src="images/tintuc/{{$tintuc->Hinh}}" class="img-reponsive" with="100" alt="">
                        <input type="file" value="" name="hinh" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Nổi Bật</label>
                        <label class="radito-inline">
                            <input type="radio" name="noibat" value="0" @if ($tintuc->Noibat == 0) {{"checked"}} @endif> Không
                        </label>
                        <label class="radito-inline">
                            <input type="radio" name="noibat" value="1" @if ($tintuc->Noibat == 1) {{"checked"}} @endif> Có
                                
                        </label>
                    </div>
                    <button type="submit" class="btn btn-default">Lưu trữ</button>
                    <button type="reset" class="btn btn-default">Làm mới</button>
                <form>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
@endsection
@section("script")
    <script>
        $(document).ready(function(){
            $("#theloai").change(function(){
                var idtl = $(this).val();
                //console.log(idt1);
                $.get("admin/ajax/loaitin/"+idtl, function(data){
                    $("#loaitin").html(data);
                });
            });
        });
    </script>
@endsection