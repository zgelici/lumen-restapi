<?php

namespace App\Http\Controllers;

use App\Company;

use Illuminate\Http\Request;
use App\Helpers\Response;

class CompaniesController extends Controller
{
    protected $companies;
    protected $request;

    public function __construct(Company $companies, Request $request)
    {
        $this->companies = $companies;
        $this->request = $request;
    }

    public function getCompanies()
    {
        $companies = $this->companies->getAll(15, $this->request);
        if ($companies) {
            return Response::json($companies);
        }
        return Response::internalError('Unable to get the company');
    }

    public function getCompany($id)
    {
        $company = $this->companies->getOne($id);
        if (!$company) {
            return Response::notFound('company not found');
        }
        return Response::json($company);
    }
//
    public function createCompany()
    {
        $validator = $this->validate($this->request, [
            'name' => 'required',
            'email' => 'required',
        ]);
        if ($validator && $validator->errors()->count()) {
            return Response::badRequest($validator->errors());
        }
        $company = $this->companies->createData($this->request);
        if ($company) {
            return Response::created($company);
        }
        return Response::internalError('Unable to create the company');
    }

    public function deleteCompany($id)
    {
        $company = $this->companies->deleteData($id);
        if (!$company) {
            return Response::internalError('Unable to delete the company');
        }
        return Response::deleted();
    }

    public function updateCompany($id)
    {
        $company = $this->companies->getOne($id);
        if (!$company) {
            return Response::notFound('Company not found');
        }

        $company = $this->companies->updateData($id, $this->request);
        if ($company) {
            return Response::json($company);
        }
        return Response::internalError('Unable to update the company');
    }
}