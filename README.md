# 抖音直播弹幕抓取

基于<a href="https://www.workerman.net" target="__blank">workerman</a>开发的超高性能PHP框架


## 使用方法

1. php 版本 >=7.2
2. 通过 composer 安装相关依赖
3. 项目根目录执行 php webman test:test start 
4. 项目启动后会启动 websocket 服务在 4200 端口，通过postman 模拟连接，发送如下消息后就能得到返回的消息
```
{
    "url":"https://live.douyin.com/619592756125"
}
```