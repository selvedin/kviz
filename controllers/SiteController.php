<?php

namespace app\controllers;

use Yii;
use app\helpers\Helper;
use app\helpers\Icons;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\PasswordResetRequestForm;
use app\models\QuizTemp;
use app\models\ResendVerificationEmailForm;
use app\models\ResetPasswordForm;
use app\models\Signup;
use app\models\SignupForm;
use app\models\User;
use app\models\VerifyEmailForm;
use InvalidArgumentException;
use yii\bootstrap5\Html;
use yii\web\BadRequestHttpException;

class SiteController extends Controller
{
    public $defaultAction = 'home';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */

    public function actionHome()
    {
        $active = QuizTemp::find()->where(['active' => 1])->select(['id', 'quiz_id'])->all();
        return $this->render('index', ['quizes' => $active]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) return $this->redirect(['home']);

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', __('Welcome to the Quiz App'));
            return $this->goBack();
        }
        $model->password = '';
        return $this->render('login', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['home']);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup($token)
    {
        $signupModel = Signup::findByToken($token);
        if (!isset($signupModel)) {
            Yii::$app->session->setFlash('danger', __('Sorry, You are have to be invited to sign up') . '.');
            return $this->redirect(['login']);
        }
        if ($signupModel->hasExpiredToken()) {
            Yii::$app->session->setFlash('danger', __('Sorry, Your invitation is expired.') . '.');
            return $this->redirect(['login']);
        }
        $model = new SignupForm();
        $model->email = $signupModel->email;
        $model->scenario = SignupForm::SCENARIO_PSWREC;
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            $signupModel->verifySignup();
            Yii::$app->session->setFlash('success', __('Thank you for registration') . '. ' . __('Please check your inbox for verification email') . '.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', __('Your email has been confirmed') . '!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', __('Sorry, we are unable to verify your account with provided token') . '.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionProfile()
    {
        $model = User::findOne(Yii::$app->user->id);
        return $this->render('../user/profile', ['model' => $model]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('info', __('Thank you for contacting us. We will respond to you as soon as possible.'));
            return $this->goHome();
        }
        return $this->render('contact', ['model' => $model]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionTrans($clear = false, $add = false)
    {
        $lang = Helper::getLang();
        $add = false;

        if ($clear) {
            file_put_contents("lang/missing_$lang.txt", "");
            return $this->redirect(['site/trans']);
        }

        $words = file_get_contents("lang/missing_$lang.txt");
        if ($this->request->isPost) {

            $words = "";
            $data = Yii::$app->request->post();
            $add = isset($data['add']);

            $array =  Helper::removeNewLine($data['words']);
            $wordsToAdd = "";

            foreach ($array as $w) {
                $w = trim(preg_replace('/\s+/', ' ', $w));
                if ($add && strpos($w, "=>") && strpos($w, "=>''") == FALSE) $wordsToAdd  .= "$w\n";
                else $words .= "'$w'=>'',\n";
            }
            if ($wordsToAdd != '') Helper::addNewTranslations($wordsToAdd);
        }
        return $this->render('trans', ['words' => $words, 'add' => $add]);
    }
}
