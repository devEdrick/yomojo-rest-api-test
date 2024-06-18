<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use Illuminate\Http\Request;

/**
 * Class CustomerController
 * @package App\Http\Controllers
 */
class CustomerController extends Controller
{
    /**
     * @var CustomerService
     */
    protected CustomerService $service;

    /**
     * CustomerController constructor.
     * @param CustomerService $service
     */
    public function __construct(CustomerService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of customers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $response = $this->service->get();

        $customers = $response['data'] ?? [];

        return view('customers.index')->with('customers', $customers);
    }

    /**
     * Show the form for creating a new customer.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $response = $this->service->create($request->all());

        $error = $response['error'] ?? null;

        if (!empty($error)) {
            return redirect()->route('customers.create')->with('error', $error['message'])->withInput();
        }

        return redirect()->route('customers.index');
    }

    /**
     * Show the form for editing the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $response = $this->service->find($id);

        $customer = $response['data'] ?? null;
        return view('customers.edit')->with('customer', $customer);
    }

    /**
     * Update the specified customer in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $response = $this->service->update($id, $request->all());

        $error = $response['error'] ?? null;

        if (!empty($error)) {
            return back()->with('error', $error['message']);
        }

        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified customer from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $response = $this->service->delete($id);

        $error = $response['error'] ?? null;

        if (!empty($error)) {
            return redirect()->route('customers.index')->with('error', $error['message']);
        }

        return redirect()->route('customers.index');
    }
}
