<?php

namespace App\Http\Controllers;

use App\Models\Org;
use Illuminate\Http\Request;
use Exception;
class OrgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orgs=Org::orderBy('org_id', 'DESC')->paginate(20);
        return view('front.orgs.orgs',compact('orgs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('front.orgs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { $messages=['name.required' =>'عنوان را وارد کنید'];
        $validatedData = $request->validate([ 'name' => 'required'],$messages);
        $org=new org();
         try{
            $org->create($request->all());
           }
        catch(Exception $exception){

                   return redirect(route('front.orgs.create'))->with('warning',$msg);
               }
               $msg="دسته بندی جدید با موفقیت انجام شد";
               return redirect(route('front.orgs'))->with('success',$msg);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function show(Org $org)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function edit(Org $org)
    {
        return view('front.orgs.edit',compact('org'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Org $org)
    { $messages=[
        'name.required' =>'عنوان را وارد کنید',

    ];
        $validatedData = $request->validate([
            'name' => 'required',
        ],$messages);

        try{
            $org->update($request->all());


        }
        catch(Exception $exception){
            switch($exception->getCode()){
                case 23000:
                    $msg="اوضیحات تکراری است";
                    break;
            }
            return redirect(route('front.orgs.edit'))->with('warning',$msg);
        }
        $msg="دسته بندی با موفقیت اصلاح شد";
        return redirect(route('front.orgs'))->with('success',$msg);
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function destroy(Org $org)
    {  try{
        $org->delete();


    }
    catch(Exception $exception){

        return redirect(route('front.orgs'))->with('warning',$exception->getCode()) ;
    }

        $msg="ایتم مورد نظر با موفقیت حذف گردید";
        return redirect(route('front.orgs'))->with('success',$msg);

    }

}
