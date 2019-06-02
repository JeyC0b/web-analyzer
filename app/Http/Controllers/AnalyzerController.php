<?php

namespace App\Http\Controllers;

use App\Analyzer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyzerController extends Controller
{
    public function index(): View
    {
        return view('analyzer/default');
    }

    public function makeAnalysis(Request $request, Analyzer $analyzer): View
    {
        if($analyzer->isUrlValidate($request->webUrl))
        {
            $analyzer->setUrl($request->webUrl);
            $analyzer->setWebpSupport($request->webpSupport);
            $analyzer->analyzeHeader();
            $analyzer->analyzeContent();
            $analyzer->scanPageSpeed();
        }

        return view('analyzer/result', $analyzer->getResult());
    }
}