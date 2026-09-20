<?php

namespace App\Http\Controllers;

use App\Models\User;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\Response;

class ExportUsersPdfController extends Controller
{
    public function __invoke(): Response
    {
        $html = view('pdf.users', [
            'users' => User::orderBy('name')->get(),
        ])->render();

        $mpdf = new Mpdf;
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('users.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="users.pdf"',
        ]);
    }
}
