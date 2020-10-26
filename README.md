<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Clone Tiktok Backend
    Clone tiktok using Laravel and Vuejs.
## 目的
    1. 主要想使用Laravel 製作server ，並做一個簡易的clone tiktok project.
    2.  由前端Vuejs 製作SPA ,並和後端Laravel 進行Request(主要由RestAPI) .
    3. deploy on Google App Engine 並使用 Google Cloud SQL , Google Cloud Storage.

### **主要支援Mobile mode ,沒有RWD.
### **ios上無法於scroll post後 auto play 正在找方法。
    
   ## Links
[ DEMO Video on YT](https://youtu.be/wb_pEa6ka9Y)   
[ FrontEnd VueJS Code Github](https://github.com/SyunSie/cloneTiktok_front)   
[  Online DEMO Hosting On GAE](https://practice-clonetiktok.df.r.appspot.com/#/) **** 沒有RWD , 請用 MOBILE MODE****
   <hr>
   
  ## 1. 架構
![理想架構 clone tiktok (1)](https://user-images.githubusercontent.com/8532735/97197596-ccc13e00-17e8-11eb-9baf-4168eac9dfee.jpg)
    
主要用 Laravel + Vuejs 建置一個server deploy 在 google App Engine，當User 要求/存取 Posts斗資料 ，會經由AppEngine(Laravel) 存/取 GCS(Google Cloud SQL)，Post_Video 會經由Laravel 存在Cloud Storage。
當前端收到Response 後 ，render在vuejs web app，而video會對Cloud Storage 進行 加載.
  
  <hr>
    
    
   

2. 主要實現功能使用MVC 實作下列功能
   - Like
   - Searching
   - Register
   - Login/Logout
   - Posts
   - Session
   - GCS(Google Cloud Storage)


