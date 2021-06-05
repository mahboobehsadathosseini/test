@extends('front.index')
@section('title')
'پنل مدیریت--اولویت بندی تیکت ها'
@endsection
@section('content')
<link rel="stylesheet" href="{{url('/front/css/app.css')}}">
<div class="content-wrapper">
    <div class="card" top=50>
        <div class="card-header border-transparent">
          <h3 class="card-title">اولویت بندی</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse">
              <i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-widget="remove">
              <i class="fa fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table m-0">
              <thead>
              <tr>
                <th>کد</th>
                <th>نام</th>
                <th>عملیات</th>
              </tr>
              </thead>
              <tbody>
                  @foreach ($rates as $rate)


              <tr>
                <td>{{ $rate->rate_id }}</td>
                <td>{{ $rate->name}}</td>
                <td><a href="{{route('front.rates.edit',$rate->rate_id)}}" class="badge badge-success">ویرایش</a>
                <a href="{{route('front.rates.destroy',$rate->rate_id)}}"
                    onclick="return confirm('ایا برای حذف مطمءن هستید');"
                    class="badge badge-warning">حذف</a></td>
              </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          {{ $rates->links()}}
        </div>
        <div class="card-footer clearfix">
          <a href="{{ route('front.rates.create') }}" class="btn btn-sm btn-info float-left">جدید</a>
        </div>
      </div>
    </div>
</div>
<aside class="control-sidebar control-sidebar-dark">
</aside>
@endsection

