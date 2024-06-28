# 抖音直播弹幕抓取

## 本项目仅作为个人学习项目。请勿用于非法用途。一切违法行为后果自负！

### 2024-06-28 更新：抓取弹幕已经正常

参考大佬项目改写 <a href="https://github.com/Sjj1024/douyin-live" target="__blank">douyin-live</a>


基于<a href="https://www.workerman.net" target="__blank">workerman</a>开发的超高性能PHP框架


## 系统要求
1. PHP >= 7.2


## 使用方法
1. 进入 websdk 目录，安装依赖 npm install
```bash
cd websdk && npm install
```
2. 启动node 服务
```bash
cd websdk && node server.js
```

3. 安装 php 依赖
```bash
composer install 
```

4. 启动 php 服务

```bash
php webman test:test start
```

5. 项目启动后会启动 websocket 服务在 4200 端口，通过postman 模拟连接，发送如下消息后就能得到返回的消息
```
{
    "url":"https://live.douyin.com/619592756125"
}
```
