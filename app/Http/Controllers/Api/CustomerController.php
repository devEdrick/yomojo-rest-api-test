<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @group Customers
 * APIs for managing customers.
 */
class CustomerController extends Controller
{
    protected $repository;

    public function __construct(CustomerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @api {get} /api/customers List all customers
     * @apiName GetCustomers
     * @apiGroup Customers
     * 
     * @apiHeader {String} Authorization Bearer <access_token>
     *
     * @apiSuccess {Object[]} customers List of customers (Array of Objects).
     * @apiSuccess {Number} customers.id Customer's unique ID.
     * @apiSuccess {String} customers.first_name Customer's first name.
     * @apiSuccess {String} customers.last_name Customer's last name.
     * @apiSuccess {Number} customers.age Customer's age.
     * @apiSuccess {Date} customers.dob Customer's date of birth (YYYY-MM-DD).
     * @apiSuccess {String} customers.email Customer's email address.
     * @apiSuccess {Timestamp} customers.creation_date Timestamp of customer creation.
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": [
     *         {
     *           "id": 1,
     *           "first_name": "John",
     *           "last_name": "Doe",
     *           "age": 30,
     *           "dob": "1992-05-15",
     *           "email": "john.doe@example.com",
     *           "creation_date": "2024-06-18 14:30:00"
     *         },
     *         {
     *           "id": 2,
     *           "first_name": "Jane",
     *           "last_name": "Smith",
     *           "age": 25,
     *           "dob": "1997-02-28",
     *           "email": "jane.smith@example.com",
     *           "creation_date": "2024-06-18 10:15:00"
     *         }
     *       ]
     *     }
     * 
     *  apiError 401 Unauthorized Error if the access token is missing or invalid.
     */
    public function index()
    {
        return ResponseHelper::success(CustomerResource::collection($this->repository->all()));
    }

    /**
     * @api {post} /api/customers Create a new customer
     * @apiName CreateCustomer
     * @apiGroup Customers
     * 
     * @apiHeader {String} Authorization Bearer <access_token>
     *
     * @apiBody {String} first_name Customer's first name.
     * @apiBody {String} last_name Customer's last name.
     * @apiBody {Number} age Customer's age.
     * @apiBody {Date} dob Customer's date of birth (YYYY-MM-DD).
     * @apiBody {String} email Customer's email address.
     *
     * @apiSuccess {Object} customer Newly created customer object.
     * @apiSuccess {Number} customer.id Customer's unique ID.
     * @apiSuccess {String} customer.first_name Customer's first name.
     * @apiSuccess {String} customer.last_name Customer's last name.
     * @apiSuccess {Number} customer.age Customer's age.
     * @apiSuccess {Date} customer.dob Customer's date of birth (YYYY-MM-DD).
     * @apiSuccess {String} customer.email Customer's email address.
     * @apiSuccess {Timestamp} customer.creation_date Timestamp of customer creation.
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 Created
     *     {
     *       "data": {
     *         "id": 1,
     *         "first_name": "John",
     *         "last_name": "Doe",
     *         "age": 32,
     *         "dob": "1992-05-15",
     *         "email": "john.doe@example.com",
     *         "creation_date": "2024-06-18 14:30:00"
     *       },
     *       "message": "Customer created successfully."
     *     }
     *
     * @apiError ValidationFailed The request parameters did not pass validation.
     * 
     * @apiError 401 Unauthorized Error if the access token is missing or invalid.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 422 Unprocessable Entity
     *     {
     *       "error": {
     *         "code" : 422,
     *         "message": "Validation failed: The email has already been taken." 
     *       }
     *     }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'age' => 'required|integer',
            'dob' => 'required|date_format:Y-m-d',
            'email' => 'required|string|email|max:50|unique:customer',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error422($validator->errors()->first());
        } 

        $customer = $this->repository->create($validator->validated());

        return ResponseHelper::success(new CustomerResource($customer), 'Customer created successfully.', Response::HTTP_CREATED);
    }

    /**
     * @api {get} /api/customers/:id Get a specific customer
     * @apiName GetCustomer
     * @apiGroup Customers
     * 
     * @apiHeader {String} Authorization Bearer <access_token>
     *
     * @apiParam {Number} id Customer's unique ID.
     *
     * @apiSuccess {Object} customer Customer object.
     * @apiSuccess {Number} customer.id Customer's unique ID.
     * @apiSuccess {String} customer.first_name Customer's first name.
     * @apiSuccess {String} customer.last_name Customer's last name.
     * @apiSuccess {Number} customer.age Customer's age.
     * @apiSuccess {Date} customer.dob Customer's date of birth (YYYY-MM-DD).
     * @apiSuccess {String} customer.email Customer's email address.
     * @apiSuccess {Timestamp} customer.creation_date Timestamp of customer creation.
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *         "id": 1,
     *         "first_name": "John",
     *         "last_name": "Doe",
     *         "age": 30,
     *         "dob": "1992-05-15",
     *         "email": "john.doe@example.com",
     *         "creation_date": "2024-06-18 14:30:00"
     *       }
     *     }
     *
     * @apiError CustomerNotFound The specified customer was not found.
     * 
     * @piError 401 Unauthorized Error if the access token is missing or invalid.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": {
     *         "code" : 404,
     *         "message": "Customer not found." 
     *       }
     *     }
     */
    public function show($id)
    {
        $customer = $this->repository->find($id);
        if (!$customer) {
            return ResponseHelper::error404('Customer not found.');
        }

        return ResponseHelper::success($customer);
    }

    /**
     * @api {put} /api/customers/:id Update a customer
     * @apiName UpdateCustomer
     * @apiGroup Customers
     * 
     * @apiHeader {String} Authorization Bearer <access_token>
     *
     * @apiParam {Number} id Customer's unique ID.
     * 
     * @apiBody {String} [first_name] Updated first name of the customer.
     * @apiBody {String} [last_name] Updated last name of the customer.
     * @apiBody {Number} [age] Updated age of the customer.
     * @apiBody {Date} [dob] Updated date of birth of the customer (YYYY-MM-DD).
     * @apiBody {String} [email] Updated email address of the customer.
     *
     * @apiSuccess {Object} customer Updated customer object.
     * @apiSuccess {Number} customer.id Customer's unique ID.
     * @apiSuccess {String} customer.first_name Customer's first name.
     * @apiSuccess {String} customer.last_name Customer's last name.
     * @apiSuccess {Number} customer.age Customer's age.
     * @apiSuccess {Date} customer.dob Customer's date of birth (YYYY-MM-DD).
     * @apiSuccess {String} customer.email Customer's email address.
     * @apiSuccess {Timestamp} customer.creation_date Timestamp of customer creation.
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *         "id": 1,
     *         "first_name": "John",
     *         "last_name": "Doe",
     *         "age": 35,
     *         "dob": "1992-05-15",
     *         "email": "john.doe@example.com",
     *         "creation_date": "2024-06-18 14:30:00"
     *       },
     *       "message": "Customer updated successfully."
     *     }
     *
     * @apiError CustomerNotFound The specified customer was not found.
     * 
     * @apiError 401 Unauthorized Error if the access token is missing or invalid.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": {
     *         "code" : 404,
     *         "message": "Customer not found." 
     *       }
     *     }
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:50',
            'last_name' => 'sometimes|required|string|max:50',
            'age' => 'sometimes|required|integer',
            'dob' => 'sometimes|required|date_format:Y-m-d',
            'email' => 'sometimes|required|string|email|max:50|unique:customer,email,' . $id,
        ]);
    
        if ($validator->fails()) {
            return ResponseHelper::error422($validator->errors()->first());
        }

        $customer = $this->repository->find($id);
        if (!$customer) {
            return ResponseHelper::error404('Customer not found.');
        }

        $updated = $this->repository->update($id, $validator->validated());
        if (!$updated) {
            return ResponseHelper::error404('Customer update failed.');
        }

        return ResponseHelper::success(new CustomerResource($updated), 'Customer updated successfully.');
    }


    /**
     * @api {delete} /api/customers/:id Delete a customer
     * @apiName DeleteCustomer
     * @apiGroup Customers
     * 
     * @apiHeader {String} Authorization Bearer <access_token>
     *
     * @apiParam {Number} id Customer's unique ID.
     *
     * @apiSuccess {String} message Success message indicating deletion.
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "message": "Customer deleted successfully."
     *     }
     *
     * @apiError CustomerNotFound The specified customer was not found.
     * 
     * @apiError 401 Unauthorized Error if the access token is missing or invalid.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": {
     *         "code" : 404,
     *         "message": "Customer not found." 
     *       }
     *     }
     */
    public function destroy($id)
    {
        $customer = $this->repository->find($id);
        if (!$customer) {
            return ResponseHelper::error404('Customer not found.');
        }

        $deleted = $this->repository->delete($customer->id);
        if (!$deleted) {
            return ResponseHelper::error404('Customer delete failed.');
        }

        return ResponseHelper::success([], 'Customer deleted successfully.');
    }
}
