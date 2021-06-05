<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;
use Exception;
class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rates=Rate::orderBy('rate_id', 'DESC')->paginate(20);
        return view('front.rates.rates',compact('rates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('front.rates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages=['name.required' =>'عنوان را وارد کنید'];
        $validatedData = $request->validate([ 'name' => 'required'],$messages);
        $rate=new rate();
         try{
            $rate->create($request->all());
           }
        catch(Exception $exception){

                   return redirect(route('front.rates.create'))->with('warning',$msg);
               }
               $msg="دسته بندی جدید با موفقیت انجام شد";
               return redirect(route('front.rates'))->with('success',$msg);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function show(Rate $rate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function edit(Rate $rate)
    {
        return view('front.rates.edit',compact('rate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rate $rate)
    {
        $messages=[
            'name.required' =>'عنوان را وارد کنید',

        ];
            $validatedData = $request->validate([
                'name' => 'required',
            ],$messages);

            try{
                $rate->update($request->all());


            }
            catch(Exception $exception){
                switch($exception->getCode()){
                    case 23000:
                        $msg="اوضیحات تکراری است";
                        break;
                }
                return redirect(route('front.rates.edit'))->with('warning',$msg);
            }
            $msg="دسته بندی با موفقیت اصلاح شد";
            return redirect(route('front.rates'))->with('success',$msg);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {  try{
        $rate->delete();


    }
    catch(Exception $exception){

        return redirect(route('front.rates'))->with('warning',$exception->getCode()) ;
    }

        $msg="ایتم مورد نظر با موفقیت حذف گردید";
        return redirect(route('front.rates'))->with('success',$msg);

    }
    }

