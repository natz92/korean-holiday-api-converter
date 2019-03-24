# Korean-Holiday API Converter
Korean-Holiday Library에 사용되는 공휴일 데이터를 [공공데이터포털](https://www.data.go.kr) `특일 정보`를 사용해 가져옵니다.

## Require
- [공공데이터포털](https://www.data.go.kr)에서 발급 받은 특일 정보 API `서비스키`가 필요합니다.

## 사용방법
> **주의:** 특일 정보 API는 2015년 공휴일부터 제공하고 있습니다.
1. `composer install`
1. `php Convert.php {API키} {추출년도}`
1. `result` 디렉터리에 `추출년도.yml` 파일 생성됨

## 주의사항
1. 2개 이상의 공휴일이 겹칠 수 있음 (해결중입니다.)
1. 특일 정보 API는 2015년 공휴일부터 제공하고 있습니다.