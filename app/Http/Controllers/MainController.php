<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        // Load users notes
        $id = session('user.id');
        $notes = User::find($id)->notes()->get()->toArray();

        // show home view
        return view('home', ['notes' => $notes]);
    }

    public function newNote()
    {
        // show new note view

        return view('new_note');
    }

    public function newNoteSubmit(Request $request)
    {
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000',
            ],
            [
                'text_title.required' => 'O título é obrigatório.',
                'text_title.min' => 'O título deve ter pelo menos :min caracteres',
                'text_title.max' => 'O título deve ter no máximo :max caracteres',

                'text_note.required' => 'O texto da nota é obrigatório.',
                'text_note.min' => 'A nota deve ter pelo menos :min caracteres',
                'text_note.max' => 'A nota deve ter no máximo :max caracteres',
            ]
        );

        // get user id (session)
        $id = session('user.id');

        // Create new note
        $note = new Note;
        $note->user_id = $id;
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        return redirect()->route('home');
    }

    public function editNote($id)
    {
        $id = Operations::decryptId($id);

        // Load note
        $note = Note::find($id);

        // show edit note view
        return view('edit_note', ['note' => $note]);
    }

    public function editNoteSubmit(Request $request)
    {
        // Validate request
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000',
            ],
            [
                'text_title.required' => 'O título é obrigatório.',
                'text_title.min' => 'O título deve ter pelo menos :min caracteres',
                'text_title.max' => 'O título deve ter no máximo :max caracteres',

                'text_note.required' => 'O texto da nota é obrigatório.',
                'text_note.min' => 'A nota deve ter pelo menos :min caracteres',
                'text_note.max' => 'A nota deve ter no máximo :max caracteres',
            ]
        );

        // check if note_id exists
        if($request->note_id == null){
            return redirect()->route('home');
        }

        // Decrypt note_id
        $id = Operations::decryptId($request->note_id);

        // Load note
        $note = Note::find($id);

        // Update note
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        // Redirect to home
        return redirect()->to(route('home'));
    }

    public function deleteNote($id)
    {
        $id = Operations::decryptId($id);

        echo "I'm delete note with id = $id";
    }
}
