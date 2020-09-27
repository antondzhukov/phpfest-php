mkdir -p ./vendor/phpfest/protoout
rm -rf ./vendor/phpfest/protoout/*
protoc --php_out=./vendor/phpfest/protoout --grpc_out=./vendor/phpfest/protoout --plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin ./vendor/phpfest/go/phpfestproto/*.proto