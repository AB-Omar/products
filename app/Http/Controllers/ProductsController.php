<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get the data from json file
        $products = file_get_contents(storage_path() . "/products.json");
        
        //decode the data and convert it as Laravel collection
        $collection_of_products = collect(json_decode($products, false));

        //sort the collection by date
        $sorted_collection_of_products = $collection_of_products->sortBy('date');
        
        if (request()->ajax()) {
            return Datatables::of($sorted_collection_of_products)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->product_id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editBook">Edit</a>';
   
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->product_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteBook">Delete</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('products.index', [
            'products' => $products
        ]);    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //map the request data to an array
        $data = array(
            'product_id' => rand(1, 100),
            'product_name'=> $request->product_name,
            'product_price'=> $request->product_price,
            'product_quantity'=> $request->product_quantity,
            'date'=> Carbon::now()->format('Y-m-d'),
        );

        //get the current products from file
        $products = file_get_contents(storage_path() . "/products.json");

        //json decode the current products
        $tempArray = json_decode($products, true);

        //check the length of the current products in the file
        if( is_countable($tempArray) && count ($tempArray) > 0) {

            array_push($tempArray, $data);

            $jsonData = json_encode($tempArray);

        }else {
            $jsonData = json_encode($data);
        }
        //endcode the temp data

        //append the current data to json
        file_put_contents(storage_path() . "/products.json" , $jsonData);


        //return respone with success message
        return response()->json(['success'=>'Products saved successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        exit();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        exit();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        exit();
    }
}
