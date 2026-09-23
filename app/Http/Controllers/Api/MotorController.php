<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Models\Tarif_rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MotorController extends Controller
{
    public function index( Request $request)
    {
        $query = Motor::with(['pemilik', 'Tarif_rental']);

        // Filter berdasarkan status motor jika ada di request
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan merk motor jika ada di request
        if ($request->has('merk')) {
            $query->where('merk', 'like', '%' . $request->merk . '%');
        }

        // Filter berdasarkan tipe cc motor jika ada di request
        if ($request->has('tipe_cc')) {
            $query->where('tipe_cc', $request->tipe_cc);
        }

        // Filter berdasarkan tarif harian motor jika ada di request
        if ($request->has('max_price')) {
            $query->whereHas('tarifRental', function($q) use ($request) {
                $q->where('tarif_harian', '<=', $request->max_price);
            });
        }

        $motors = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $motors,
        ], 200);
    }

    // Menampilkan detail motor berdasarkan ID
    public function show($id)
    {
        $motor = Motor::with(['pemilik', 'tarifRental'])->find($id);

        if (!$motor) {
            return response()->json([
                'success' => false,
                'message' => 'Motor not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $motor
        ], 200);
    }

    // Menambahkan data motor
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'merk' => ['required', 'max:100'],
            'tipe_cc' => ['required', Rule::in(['100', '125', '150'])],
            'no_plat' => ['required', 'max:8'],
            'status' => ['required', Rule::in(['tersedia', 'disewa'])],
            'dokumen_kepemilikan' => ['required', Rule::in(['LENGKAP', 'TIDAK LENGKAP'])],
            'tarif_harian' => ['required', 'min:0'],
            'tarif_mingguan' => ['required', 'min:0'],
            'tarif_bulanan' => ['required', 'min:0'],
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        try {
            $motordata = [
                'pemilik_id' => $request->penggunas()->id,
                'penggunas_id' => $request->penggunas()->id,
                'merk' => $request->merk,
                'tipe_cc' => $request->tipe_cc,
                'no_plat' => $request->no_plat,
                'status' => 'tersedia',
                'dokumen_kepemilikan' => $request->dokumen_kepemilikan,
            ];

            if ($request->hasFile('foto_motor')) {
                $path = $request->file('foto_motor')->store('foto_motor', 'public');
                $motordata['foto_motor'] = $path;
            }

            $motor = Motor::create($motordata);

            Tarif_rental::create([
                'motor_id' => $motor->id,
                'tarif_harian' => $request->tarif_harian,
                'tarif_mingguan' => $request->tarif_mingguan,
                'tarif_bulanan' => $request->tarif_bulanan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Motor berhasil ditambahkan',
                'data' => $motor->load('tarifRental'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan motor: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function create()
    {
        return view('pages.motor.create');
    }

    // Mengedit data motor
    public function update(Request $request, $id)
    {
        $motor = Motor::where('pemilik_id', $request->penggunas()->id)->find($id);

        if (!$motor) {
            return response()->json([
                'success' => false,
                'message' => 'Motor not found or you do not have permission to edit this motor'
            ], 404);
        }

        $validator = Validator::make($request->all(),[
            'merk' => ['sometimes', 'max:100'],
            'tipe_cc' => ['sometimes', Rule::in(['100', '125', '150'])],
            'no_plat' => ['sometimes', 'max:8'].$id,
            'foto_motor' => ['sometimes', 'image'],
            'status' => ['sometimes', Rule::in(['tersedia', 'disewa'])],
            'dokumen_kepemilikan' => ['sometimes', Rule::in(['LENGKAP', 'TIDAK LENGKAP'])],
            'tarif_harian' => ['sometimes', 'numeric', 'min:0'],
            'tarif_mingguan' => ['sometimes', 'numeric', 'min:0'],
            'tarif_bulanan' => ['sometimes', 'numeric', 'min:0'],
        ]);

            if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

            try {
                // Update motor data
                $motor->update($request->only(['merk', 'tipe_cc', 'no_plat', 'status', 'dokumen']));

                // Handle foto upload
                if ($request->hasFile('foto')) {
                    // Delete old photo
                    if ($motor->foto) {
                        Storage::disk('public')->delete($motor->foto);
                    }
                    $path = $request->file('foto')->store('motors', 'public');
                    $motor->update(['foto' => $path]);
                }

                // Update tarif if provided
                if ($request->has('tarif_harian') || $request->has('tarif_mingguan') || $request->has('tarif_bulanan')) {
                    $motor->tarifRental()->update($request->only(['tarif_harian', 'tarif_mingguan', 'tarif_bulanan']));
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Motor berhasil diupdate',
                    'data' => $motor->load('tarifRental')
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate motor',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    
    public function edit($id)
    {
        $motor = Motor::findOrFail($id);

        return view('pages.motor.edit', [
            'motor' => $motor,
        ]);
    }



    public function destroy(Request $request, $id)
    {
        $motor = Motor::where('pemilik_id', $request->user()->id)->find($id);

        if (!$motor) {
            return response()->json([
                'success' => false,
                'message' => 'Motor not found or unauthorized'
            ], 404);
        }

        // Check if motor is currently rented
        if ($motor->status === 'disewa') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete motor that is currently rented'
            ], 400);
        }

        try {
            // Delete photo if exists
            if ($motor->foto) {
                Storage::disk('public')->delete($motor->foto);
            }

            $motor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Motor berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus motor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}