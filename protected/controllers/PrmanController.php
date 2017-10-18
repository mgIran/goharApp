<?php

class PrmanController extends Controller
{
	public function actionReceive($text, $from, $to, $smsId, $userid)
	{
		$from = strpos($from, '98') === 0 ? $from : '98' . $from;
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