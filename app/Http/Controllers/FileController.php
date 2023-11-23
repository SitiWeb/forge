<?php

namespace App\Http\Controllers;
use App\Models\File;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
class FileController extends Controller
{

    public function index()
    {
        $files = File::all(); // Retrieve all uploaded files from the database

        return view('files.index', ['files' => $files]);
    }

    public function delete($id)
    {
        $file = File::find($id); // Find the file by its ID in the database

        if (!$file) {
            abort(404); // Handle the case where the file does not exist
        }

        // Delete the file from the storage system
        Storage::delete($file->storage_path);

        // Delete the file record from the database
        $file->delete();

        return redirect()->route('files.index')->with('success', 'File deleted successfully');
    }
    
    public function upload(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|max:2048', // Adjust the max file size as needed
        ]);

        

        $file = $request->file('file');
        
        $filename = $file->store('public/uploads');
  
        // Save metadata about the uploaded file in the database
        $savedFile = new File();
        $savedFile->filename = $file->hashName(); // Store the file's hashed name
        $savedFile->original_filename = $file->getClientOriginalName();
        $savedFile->mime_type = $file->getClientMimeType();
        $savedFile->storage_path = $filename;
        $savedFile->save();

        return redirect()->route('showFile', ['filename' => $savedFile->filename]);
    }

    public function show($filename)
    {
        $file = File::where('filename', $filename)->first();

        if (!$file) {
            abort(404); // Handle the case where the file does not exist
        }
    
        $fileContents = Storage::get($file->storage_path);
    
        // Set the Content-Type header based on the file's MIME type
        $mimeType = $file->mime_type;
    
        // Set the Content-Disposition header to force download with the original filename
        return Response::stream(function () use ($fileContents) {
            echo $fileContents;
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . $file->original_filename . '"',
            'Content-Length' => strlen($fileContents),
        ]);
    }

    public function createConfigFile($site){
        $site = Site::where('site_id',$site)->first();
        dd($site->database);
        // Define the input and output file paths
        $inputFilePath = base_path().'\config.yaml'; // Replace with your input file path
        $outputFilePath = base_path().'\output.yaml'; // Replace with your desired output file path

        // Read the content of the input file
        $inputContent = file_get_contents($inputFilePath);

        // Define an array of replacements (e.g., [id] => replacement_value)
        $replacements = [
            '[hostname]' => 'myhostname',
            '[prefix]' => 'myprefix',
            '[source_directories]' => '/path/to/source',
            // Add more replacements as needed
        ];

        // Perform the replacements in the content
        $modifiedContent = str_replace(array_keys($replacements), array_values($replacements), $inputContent);
    
        // Save the modified content to the output file
        file_put_contents($outputFilePath, $modifiedContent);

        // Optionally, display a success message
        echo "File saved as $outputFilePath\n";
    }
}
