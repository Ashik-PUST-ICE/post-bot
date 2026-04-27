<?php

namespace App\Http\Services;

use App\Models\FileManager;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    use ResponseTrait;

    public function userDetails($id)
    {
        return User::find($id);
    }

    public function userData($id = NULL)
    {
        $id = is_null($id) ? auth()->id() : $id;
        return User::where('id', $id)->first();
    }

    public function smsVerify($request)
    {

        $otp = $request->opt_field[0] . $request->opt_field[1] . $request->opt_field[2] . $request->opt_field[3] . $request->opt_field[4] . $request->opt_field[5];
        $user = User::where('id', auth()->id())->first();
        //check otp validity
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        if ($user->otp_expiry && $currentDateTime < $user->otp_expiry) {
            if ($user->otp == $otp) {
                $user->phone_verification_status = 1;
                $user->save();
                return $this->success([], __("OTP verify successful"));
            } else {
                return $this->error([], __("OTP is Invalid!"));
            }
        } else {
            return $this->error([], __("OTP time expiry!"));
        }
    }

    public function profileUpdate($request)
    {
        $authUser = auth()->user();
        DB::beginTransaction();
        try {
            $userData = [
                'name' => $request['name'],
                'email' => $request['email'],
                'mobile' => $request['mobile'],
                'currency' => $request['currency'],
                'country' => $request['country'],
                'city' => $request['city'],
                'zip_code' => $request['zip_code'],
                'address' => $request['address'],
                'company_name' => $request['company_name'],
                'company_designation' => $request['company_designation'],
                'company_country' => $request['company_country'],
                'company_city' => $request['company_city'],
                'company_zip_code' => $request['company_zip_code'],
                'company_address' => $request['company_address'],
            ];
            $pass1 = $request->get('pass1', '');
            $pass2 = $request->get('pass2', '');
            if ($pass1 != '' && $pass2 != '') {
                if ($pass1 != $pass2) {
                    DB::rollBack();
                    return $this->error([], __("Password does not Match"));
                } else {
                    $authUser->password = Hash::make($request->pass1);
                }
            }
            if ($request->hasFile('image')) {
                $existFile = FileManager::where('id', $authUser->image)->first();
                if ($existFile) {
                    $existFile->removeFile();
                    $uploaded = $existFile->upload('User', $request->image, '', $existFile->id);
                } else {
                    $newFile = new FileManager();
                    $uploaded = $newFile->upload('User', $request->image);
                }
                if ($uploaded) {
                    $userData['image'] = $uploaded->id;
                } else {
                    throw new Exception(__('Image Not Uploaded.'));
                }
            }

            if ($request->hasFile('company_logo')) {
                $existFile = FileManager::where('id', $authUser->company_logo)->first();
                if ($existFile) {
                    $existFile->removeFile();
                    $uploaded = $existFile->upload('User', $request->company_logo, '', $existFile->id);
                } else {
                    $newFile = new FileManager();
                    $uploaded = $newFile->upload('User', $request->company_logo);
                }
                if ($uploaded) {
                    $userData['company_logo'] = $uploaded->id;
                } else {
                    throw new Exception(__('Image Not Uploaded.'));
                }
            }

            $authUser->update($userData);
            DB::commit();
            return $this->success([], getMessage(__(UPDATED_SUCCESSFULLY)));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage(__(SOMETHING_WENT_WRONG)));
        }
    }

    public function addInstitution(Request $request)
    {
        $authUser = auth()->user();
        $data = $request->validate([
            "passing_year" => 'bail|required|max:195',
            "degree" => 'bail|required|max:195',
            "institute" => 'bail|required|max:195',
        ]);

        try {
            DB::beginTransaction();

            $authUser->institutions()->create([
                'passing_year' => $data['passing_year'],
                'degree' => $data['degree'],
                'institute' => $data['institute'],
            ]);

            DB::commit();
            return $this->success([], getMessage(CREATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function changePasswordUpdate(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'password' => 'bail|required|min:6|confirmed',
        ]);

        try {
            $hashedPassword = Auth::user()->password;

            if (Hash::check($request->current_password, $hashedPassword)) {
                DB::beginTransaction();
                $user = User::find(Auth::id());
                $user->password = Hash::make($request->password);
                $user->save();
                DB::commit();
                return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
            } else {
                return $this->error([], "Current password dose not match!");
            }
        } catch (Exception $e) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function settingUpdate(Request $request)
    {
        try {
            auth()->user()->update([$request->key => $request->value]);
            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function customerListAll()
    {
        $data = User::where('role', USER_ROLE_ADMIN)
            ->orderByDesc('users.id');

        return datatables($data)
            ->addIndexColumn()
            ->addColumn('name', function ($data) {
                return '<h4 class="fs-14 fw-400 lh-24 text-para-text">' . htmlspecialchars($data->name) . ' </h4>';
            })
            ->addColumn('email', function ($data) {
                return $data->email ?? __("N/A");
            })
            ->addColumn('created_at', function ($data) {
                return date('d-m-Y', strtotime($data->created_at));
            })
            ->addColumn('country', function ($data) {
                if(is_null($data->country)){
                    return __("N/A");
                }
                return getCountry($data->country);
            })
            ->addColumn('status', function ($data) {
                if ($data->status == STATUS_ACTIVE) {
                    return '<p class="zBadge zBadge-active">' . __('Active') . '</p>';
                } elseif ($data->status == STATUS_SUSPENDED) {
                    return '<p class="zBadge zBadge-cancel">' . __('Suspended
                    ') . '</p>';
                }
            })
            ->addColumn('action', function ($data) {
                return '<div class="d-flex justify-content-end align-items-center g-5">
                                <a href="' . route('super-admin.user.details', $data->id) . '"  class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke" title="Details">
                                    <img src="' . asset('assets/images/icon/eye.svg') . '" alt="edit" />
                                </a>

                                <a href="' . route('super-admin.user.edit', $data->id) . '"  class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke" title="Edit">
                                    <img src="' . asset('assets/images/icon/edit-black.svg') . '" alt="edit" />
                                </a>

                                <a href="' . route('super-admin.user.suspend', $data->id) . '" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke" title="Unsuspend">
                                    <img src="' . asset('assets/images/icon/close.svg') . '" alt="cancel" />
                                </a>

                                <button onclick="deleteItem(\'' . route('super-admin.user.delete', $data->id) . '\', \'userTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-transparent" title="Delete">
                                    <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                                </button>
                        </div>';
            })
            ->rawColumns(['name', 'country', 'action', 'status'])
            ->make(true);
    }

    public function details($id)
    {
        return User::with('userDetail')->findOrFail($id);
    }

    public function delete($id)
    {
        try {
            $customer = User::where('id', $id)->firstOrFail();
            $customer->delete();
            DB::beginTransaction();
            DB::commit();
            $message = getMessage(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }
}
