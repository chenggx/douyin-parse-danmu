const jsdom = require('jsdom');
const { JSDOM } = jsdom;

// 创建一个JSDOM实例
const dom = new JSDOM(`<!DOCTYPE html><html><body></body></html>`);
global.window = dom.window;
global.document = dom.window.document;
global.navigator = dom.window.navigator;

const http = require('http');
const  getSignature  = require('./webmssdk'); // 假设你已经将webmssdk.js导出为一个函数
console.log('@@@@',getSignature)
const server = http.createServer((req, res) => {
    if (req.url === '/signature' && req.method === 'POST') {
        let body = [];
        req.on('data', (chunk) => {
            body.push(chunk);
        }).on('end', () => {
            body = Buffer.concat(body).toString();
            try {
                const signature = getSignature(body); // 假设getSignature接受数据并返回签名
                res.writeHead(200, {'Content-Type': 'text/plain'});
                res.end(signature);
            } catch (err) {
                res.writeHead(500, {'Content-Type': 'text/plain'});
                res.end('Error');
            }
        });
    } else {
        res.writeHead(404, {'Content-Type': 'text/plain'});
        res.end('Not Found');
    }
});

server.listen(3010, () => {
    console.log('Server running at http://localhost:3010/');
});