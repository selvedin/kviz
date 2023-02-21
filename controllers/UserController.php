<?php

namespace app\controllers;

use app\models\Perms;
use app\models\search\UserSearch;
use Yii;
use app\models\Signup;
use app\models\User;
use app\models\SignupForm;
use app\models\VerifyEmailForm;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                        'send-signup' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $perms = new Perms();
        if (!$perms->canList('User')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Activates a single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionActivate($id)
    {

        $perms = new Perms();
        if (!$perms->canDelete('User')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model = $this->findModel($id);
        $verify = new VerifyEmailForm($model->verification_token);
        if (!$verify->verifyEmail()) {
            Yii::$app->session->setFlash('error', __('Sorry, we are unable to verify your account with provided token') . '.');
            return $this->goHome();
        }
        Yii::$app->session->setFlash('success', __('Your email has been confirmed') . '!');
        return $this->goHome();
        return $this->render('view', ['model' => $model]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $perms = new Perms();
        if (!$perms->canView('User')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $perms = new Perms();
        if (!$perms->canUpdate('User')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionSendSignup()
    {
        $perms = new Perms();
        if (!$perms->canCreate('User')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model = new Signup();
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            if ($model->isRegistered()) {
                Yii::$app->session->setFlash('warning', __('User already registered') . '. ');
                return $this->goHome();
            }
            $model->generateToken();
            if ($model->save() && $model->sendEmail()) {
                Yii::$app->session->setFlash('success', __('Signup form sent') . '. ');
                return $this->goHome();
            }
        }
        Yii::$app->session->setFlash('danger', __('Something went wrong') . '. ');
        return $this->goHome();
    }



    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $perms = new Perms();
        if (!$perms->canCreate('User')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model = new SignupForm();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->signup()) {
                $user = User::findByUsername($model->username);
                return $this->redirect(['view', 'id' => $user->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($id)
    {
        $token = User::generatePasswordToken($id);
        return $this->redirect(['site/reset-password', 'token' => $token]);
    }

    public function actionImportUsers()
    {
        $perms = new Perms();
        if (!$perms->canDelete('User')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));

        $odlUsers = Yii::$app->db->createCommand("SELECT * from wp_users")->queryAll();
        return $this->render('import', ['old' => $odlUsers]);

        throw new NotFoundHttpException("Page does not exist!");
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(__('The requested page does not exist.'));
    }
}
