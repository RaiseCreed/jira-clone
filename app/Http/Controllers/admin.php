<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class admin extends Controller
{
  
    public function getDiskUsage()
    {
        
        $attachmentsDirectory = storage_path('app/attachments');

        
        if (File::exists($attachmentsDirectory)) {
            
            $totalSize = collect(File::allFiles($attachmentsDirectory))
                         ->sum(function ($file) {
                             return $file->getSize();
                         });

           
            $totalSpace = 1024 * 1024 * 1024;
            
            // procentowe zapełnienie
            $usedPercentage = ($totalSize / $totalSpace) * 100;

            return [
                'usedSpace' => $totalSize,
                'totalSpace' => $totalSpace,
                'usedPercentage' => number_format($usedPercentage, 2),
            ];
        }

       
        return [
            'usedSpace' => 0,
            'totalSpace' => 1024 * 1024 * 1024, // 1GB
            'usedPercentage' => 0,
        ];
    }

   
    public function showDashboard()
    {
      
        $diskUsage = $this->getDiskUsage();

     
        return view('admin.dashboard', compact('diskUsage'));
    }
}
