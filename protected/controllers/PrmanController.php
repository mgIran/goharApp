<?php

class PrmanController extends Controller
{
	public function actionReceive($text ,$from ,$to ,$smsId ,$userid)
	{
		$model = new TextMessagesReceive();
		$model->date = time();
		$model->sender = $from;
		$model->text = $text;
		$model->to = $to;
		$model->sms_id = $smsId;
		$model->prman_user_id = $userid;
		$model->save();
	}
}