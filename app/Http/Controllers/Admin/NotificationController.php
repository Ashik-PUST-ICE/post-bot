<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationSeen;
use App\Models\NotificationTemplates;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    use ResponseTrait;

    public function notificationView($id)
    {
        $data['pageTitle'] = 'Notification View';
        $data['singleNotification'] = Notification::where('user_id', auth()->id())->find($id);

        if($data['singleNotification'] !=null){
            $dataArray = [
                'user_id'=> $data['singleNotification']->user_id,
                'notification_id'=> $data['singleNotification']->id,
            ];
            NotificationSeen::firstOrCreate($dataArray);
        }
        return view('admin.notification.view', $data);
    }
    public function notificationDelete($id)
    {
        DB::beginTransaction();
        try {
            $data= Notification::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
            if (!$data && $data == null) {
                return $this->error([], SOMETHING_WENT_WRONG);
            }
            $data->delete();
            DB::commit();
            return redirect()->back()->with('success', DELETED_SUCCESSFULLY);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', SOMETHING_WENT_WRONG);
        }
    }

    public function allNotification(){
        $data['pageTitle'] = 'All Notification';
        return view('admin.notification.all', $data);
    }
    public function notificationMarkAsRead($id){
        DB::beginTransaction();
        try {
            $notificationData = Notification::where('user_id', auth()->id())->where('id','=',$id)->first();
            $dataArray = [
                'user_id'=> $notificationData->user_id,
                'notification_id'=> $notificationData->id,
            ];
            NotificationSeen::firstOrCreate($dataArray);

            if($notificationData->link != NULL){
                return redirect()->to($notificationData->link);
            }
            DB::commit();
            return redirect()->back()->with('success', UPDATED_SUCCESSFULLY);
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('error', SOMETHING_WENT_WRONG);

        }

    }
    public function notificationMarkAllAsRead(){
        DB::beginTransaction();
        try {
            foreach (userNotification('unseen') as $item){
                $dataArray = [
                    'user_id'=> auth()->id(),
                    'notification_id'=> $item->id,
                ];
                NotificationSeen::firstOrCreate($dataArray);
            }
            DB::commit();
            return redirect()->back()->with('success', UPDATED_SUCCESSFULLY);
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('error', SOMETHING_WENT_WRONG);
        }
    }

    public function notifyTemplate()
    {
        $data['pageTitle'] = __('Notification Template');
        $data['title'] = __('Notification Template');
        $data['activeNotifySetting'] = 'active';

        $data['notifyTemplates'] = NotificationTemplates::where('tenant_id', auth()->user()->tenant_id)->get();

        return view('admin.setting.notify_temp.notify-temp', $data);
    }

    public function notifyTemplateConfig(Request $request)
    {
        try {
            $data['template'] = NotificationTemplates::where('tenant_id', auth()->user()->tenant_id)->find($request->id);
            $data['fields'] = \customNotifyTempFields($data['template']->slug);
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function notifyTemplateConfigUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $notifyTemplate = NotificationTemplates::where('tenant_id', auth()->user()->tenant_id)->findOrFail($request->id);
            $notifyTemplate->title = $request->title;
            $notifyTemplate->tenant_id = auth()->user()->tenant_id;
            $notifyTemplate->body = $request->body;
            $notifyTemplate->save();

            DB::commit();
            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

}
