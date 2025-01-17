<?php
namespace App\Http\Controllers\Api;

use App\Services\Api\PersonalDataService;
use Illuminate\Http\Request;

class PersonalDataController
{
    /*定义service变量*/
    protected $service;

    /**
     * 个人信息构造方法
     *
     * PersonalDataController constructor.
     * @param PersonalDataService $personalDataService
     */
    public function __construct(PersonalDataService $personalDataService)
    {
        $this->service = $personalDataService;
    }

    /**
     * 通过code微信换取身份openid
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPersonalOpenIdByCode(Request $request)
    {
        return $this->service->getPersonalOpenIdByCode($request->input('code'));
    }

    /**
     * 同步微信用户信息到服务端
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncPersonalData(Request $request)
    {
        return $this->service->syncPersonalData($request->input());
    }

    /**
     * 检查用户是否登录过
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkLogin(Request $request)
    {
        return $this->service->checkLogin($request->input());
    }

    /**
     * 获取微信授权的手机号
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPhoneNumber(Request $request)
    {
        return $this->service->getPhoneNumber($request->input());
    }

    /**
     * 绑定手机号
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        return $this->service->register($request->input());
    }

    /**
     * 个人信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo(Request $request)
    {
        return $this->service->getUserInfo($request->input());
    }

    /**
     * 我的二维码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserCode(Request $request)
    {
        return $this->service->getUserCode($request->input());
    }
}