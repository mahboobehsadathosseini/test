@extends('front.index')
@section('title')
'پنل مدیریت--اصلاح اولویت بندی'
@endsection
@section('content')
<link rel="stylesheet" href="{{url('/front/css/app.css')}}">
<div class="content-wrapper">
    <div class="card" top=50>
        <div class="card-header border-transparent">
          <h3 class="card-title">اصلاح اولویت بندی</h3>

          <div class="card-tools">
          </div>
        </div>
        <div class="card-body p-0">
            <form action="{{route('front.rates.update',$rate->rate_id)}}" method="POST">
                @csrf
          <div class="form-group">
           <label>نام</label>
           <input type="text" "form-control @error('name') is-invalid @enderror"  name="name"  value="{{$rate->name}}">
          </div>
          @error('name')
          <div class="alert alert-danger">{{ $message }}</div>
          @enderror
        <div class="form-group">
            <button type="submit" class="btn btn-success" >ذخیره</button>
            <a href="{{route('front.rates')}}"  class="btn btn-warning">انصراف</a>
           </div>

          </form>
        <div class="card-footer clearfix">

        </div>
      </div>
    </div>
</div>
<aside class="control-sidebar control-sidebar-dark">
</aside>
@endsection

