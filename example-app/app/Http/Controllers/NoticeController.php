<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        // Assuming this method lists all notices
        $notices = Notice::all();
        return view('admin.notices.index', compact('notices'));
    }

    public function create()
    {
        // Method to show the form for creating a new notice
        return view('admin.notices.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Create a new Notice instance
        $notice = new Notice();
        $notice->title = $request->title;
        $notice->content = $request->content;
        // You can add more fields if necessary

        // Save the Notice
        if ($notice->save()) {
            // If saved successfully, return success response
            return response()->json([
                'success' => true,
                'message' => 'Notice added successfully!',
                'notice' => $notice // Optionally, you can send back the created notice
            ]);
        } else {
            // If failed to save, return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to add notice. Please try again.'
            ]);
        }
    }

    public function edit(Notice $notice)
    {
        return view('admin.notices.edit', compact('notice'));
    }

    // NoticeController.php
    public function update(Request $request, Notice $notice)
    {
        // Validate and update notice
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $notice->update($validated);

        return response()->json(['success' => true, 'message' => 'Notice updated successfully!']);
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();

        return response()->json(['success' => true, 'message' => 'Notice deleted successfully!']);
    }

}
