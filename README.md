<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Clone Tiktok backend
    Clone tiktok using Laravel and Vuejs.
    
### 主要想使用Laravel 製作server ，並做一個簡易的clone tiktok project.
### 主要支援Mobile mode ,沒有RWD.
### ios上無法於scroll post後 auto play 正在找方法。
    
    ##Demo video
   
   
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

    


<hr/>
## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
