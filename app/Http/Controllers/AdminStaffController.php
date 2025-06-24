<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Doctor;
use App\Models\Nurse;
use App\Models\HospitalSection;

class AdminStaffController extends Controller
{
    public function index(Request $request)
    {
        // Get all sections for the filter
        $sections = HospitalSection::all();

        // Build the query dynamically
        $query = Staff::query();

        if ($request->filled('section')) {
            $query->where('working_section', $request->section);
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        if ($request->filled('role') && is_array($request->role)) {
            $query->whereIn('role', $request->role);
        }

        // ✅ Move this BEFORE get()
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
            });
        }

        // ✅ Only get() once, after all filters are applied
        $staff = $query->get();

        return view('admin.manage-staff', [
            'sections' => $sections,
            'staff' => $staff,
            'request' => $request, // pass request for form repopulation
        ]);
    }



    public function edit($id)
    {
        // Fetch staff details with section name
        $staff = Staff::leftJoin('hospital_section', 'staff.working_section', '=', 'hospital_section.section_id')
                      ->select('staff.*', 'hospital_section.section_name')
                      ->where('staff.staff_id', $id)
                      ->firstOrFail();

        // Check if doctor or nurse
        $doctor = Doctor::where('staff_id', $id)->first();
        $nurse = Nurse::where('staff_id', $id)->first();

        return view('admin.edit-staff', [
            'staff' => $staff,
            'doctor' => $doctor,
            'nurse' => $nurse,
        ]);
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::where('staff_id', $id)->firstOrFail();

        $staff->full_name = $request->full_name;
        $staff->date_of_birth = $request->date_of_birth;
        $staff->age = $request->age;
        $staff->gender = $request->gender;
        $staff->phone_no = $request->phone_no;
        $staff->email = $request->email;
        $staff->emergency_contact = $request->emergency_contact;
        $staff->emergency_relationship = $request->emergency_relationship;
        $staff->role = $request->role;
        $staff->position = $request->position;

        if ($staff->save()) {
            // Check if doctor or nurse
            $doctor = Doctor::where('staff_id', $id)->first();
            $nurse = Nurse::where('staff_id', $id)->first();

            if ($doctor) {
                $doctor->doc_specialisation = $request->specialization ?? '';
                $doctor->doc_qualification = $request->qualification ?? '';
                $doctor->save();
            }

            if ($nurse) {
                $nurse->nurse_specialisation = $request->specialization ?? '';
                $nurse->nurse_qualification = $request->qualification ?? '';
                $nurse->save();
            }

            return redirect()->route('admin.manage-staff')->with('success', 'Profile updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Error updating profile');
        }
    }


    public function remove($id)
    {
        // Delete related doctor or nurse records first (optional, if foreign key constraints not set)
        Doctor::where('staff_id', $id)->delete();
        Nurse::where('staff_id', $id)->delete();

        // Delete staff
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('admin.manage-staff')->with('success', 'Staff member successfully removed.');
    }


    public function create()
    {
        // If you need to pass sections or other data to the registration form
        $sections = HospitalSection::all();

        return view('admin.register-staff', [
            'sections' => $sections,
        ]);
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'phone_no' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'emergency_contact' => 'required|string|max:255',
            'emergency_relationship' => 'required|string|max:255',
            'role' => 'required|string',
            'position' => 'nullable|string|max:255',
            'section_id' => 'required|exists:hospital_section,section_id',
            'specialisation' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
        ]);


        $staff = Staff::create([
            'full_name' => $validated['fname'],
            'patient_id' => $validated['mykad'],
            'date_of_birth' => $validated['dob'],
            'gender' => $validated['gender'],
            'phone_no' => $validated['phone_no'],
            'email' => $validated['email'],
            'emergency_contact' => $validated['emergency_contact'],
            'emergency_relationship' => $validated['emergency_relationship'],
            'role' => $validated['role'],
            'position' => $validated['position'],
            'working_section' => $validated['section_id'],
        ]);


        // Create Staff
        // $staff = new Staff();
        // $staff->full_name = $request->fname;
        // $staff->date_of_birth = $request->dob;
        // $staff->gender = $request->gender;
        // $staff->phone_no = $request->phone_no;
        // $staff->email = $request->email;
        // $staff->emergency_contact = $request->emergency_contact;
        // $staff->emergency_relationship = $request->emergency_relationship;
        // $staff->role = $request->role;
        // $staff->position = $request->position;
        // $staff->working_section = $request->section_id;
        // $staff->save();

        // Create role-specific record
        if ($request->role === 'Doctor') {
            Doctor::create([
                'staff_id' => $staff->staff_id,
                'doc_specialisation' => $request->specialisation,
                'doc_qualification' => $request->qualification,
            ]);
        } elseif ($request->role === 'Nurse') {
            Nurse::create([
                'staff_id' => $staff->staff_id,
                'nurse_specialisation' => $request->specialisation,
                'nurse_qualification' => $request->qualification,
            ]);
        }

        return redirect()->route('admin.manage-staff')->with('success', 'Staff registered successfully.');
    }
}
