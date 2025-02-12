<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        return view('admin.faq.index', ['faq' => $this->getFaqs()]);
    }

    public function remove(Faq $faq)
    {
        $faq->delete();

        return [
            'status' => 'success',
            'faq' => $this->getFaqs()
        ];
    }

    public function update(Request $request, ?Faq $faq = null)
    {
        if (!$faq) {
            $faq = new Faq();
        }

        $data = $request->post('data');

        if (!empty($data['pro_koho'])) {
            $faq->pro_koho = $data['pro_koho'];
        } else {
            $faq->pro_koho = $data['pro_koho_new'];
        }
        $faq->otazka = $data['otazka'];
        $faq->odpoved = $data['odpoved'];
        $faq->save();

        return [
            'status' => 'success',
            'faq' => $this->getFaqs()
        ];
    }

    private function getFaqs()
    {
        return Faq::orderBy('pro_koho', 'asc')->orderBy('otazka', 'asc')->orderBy('id', 'desc')->get();
    }
}
