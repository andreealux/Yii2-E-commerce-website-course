<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'updated-address', 'updated-account'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(){
        /** @var @var \common\models\User $user */
        $user = Yii::$app->user->identity;
        $userAddresses = $user->addresses;
        $userAddress = $user->getAddress();

        return $this->render('profile',[
            'user' => $user,
            'userAddress' => $userAddress
        ]);
    }

    public function actionUpdateAddress(){
        if(!Yii::$app->request->isAjax){
            throw new ForbiddenHttpException();
        }
        $user = Yii::$app->user->identity;
        $userAddress = $user->getAddress();
        $success = false;
        if($userAddress->load(Yii::$app->request->post()) && $userAddress->save()){
            $success = true;
        }
        return $this->renderAjax('user_address', [
            'userAddress' => $userAddress,
            'success' => $success
        ]);
    }

    public function actionUpdateAccount(){
        if(!Yii::$app->request->isAjax){
            throw new ForbiddenHttpException();
        }
        $user = Yii::$app->user->identity;

        $success = false;
        if($user->load(Yii::$app->request->post()) && $user->save()){
            $success = true;
        }
        return $this->renderAjax('user_account', [
            'user' => $user,
            'success' => $success
        ]);
    }
}