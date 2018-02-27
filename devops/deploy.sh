#!/bin/bash

set -xe

#export REMOTE_HOST="vm.app"
#export REMOTE_ROOT="/data/dnf"
#(cd ${WORKSPACE}/devops/;chmod +x *.sh; ./test.sh)

SERVICE_NAME="sys"
TAR_NAME="${SERVICE_NAME}-${BUILD_ID}-`date +%y%m%d`"
TAR_GZ="${TAR_NAME}.tar.gz"
ARR_HOST=($REMOTE_HOST)

type="dev";
REMOTE_PATH="${REMOTE_ROOT}/$type"

## 需要构建的厅, 必填； 用于发布指定厅而不影响其它厅##
echo "======BUILD_HALL_LIST========"$BUILD_HALL_LIST;

START_FILE="start.sh";

cd ${WORKSPACE}/devops
rm -rf *.tar.gz

echo $BUILD_ID>"../build_id.txt"
echo $GIT_COMMIT>"../git_commit.txt"

#find ../docker -name '*.sh'|xargs chmod +x
#find ../devops -name '*.sh'|xargs chmod +x
#find ../docker -name '*.sh' -or -name "Dockerfile"|xargs dos2unix

tar -czf ${TAR_GZ} -C .. . \
    --exclude=.git --exclude=common/.git --exclude=vendor/.git\
    --exclude=common/config/main[-_]*.php --exclude=common/config/params[-_]*.php\
    --exclude=docker/.env-*\
    --exclude=devops/*.tar.gz

for h in ${ARR_HOST[@]}
do
(
    ssh $h sudo mkdir -p ${REMOTE_PATH}
    scp ${WORKSPACE}/devops/${TAR_GZ} $h:/tmp/
    ssh $h sudo mv /tmp/${TAR_GZ} ${REMOTE_PATH}/
)
(ssh $h "cd ${REMOTE_PATH};
    sudo mkdir ${TAR_NAME};
    sudo tar xzf ${TAR_GZ}  -C ${TAR_NAME};
    if [ ! -d "$SERVICE_NAME" ]; then
        sudo ln -s ${TAR_NAME} ${SERVICE_NAME};
    else
        #使用分厅部署， 取消全量部署
        PUBLISH_ALL=\"\";
       if [ $PUBLISH_TYPE != part ]; then
           (cd ${SERVICE_NAME}/docker/; for yaml in *.yaml; do sudo docker-compose -f \$yaml stop ;done;)
           PUBLISH_ALL=\"publish_all\"
       fi

        sudo rm -rf ${SERVICE_NAME};
        sudo ln -s ${TAR_NAME} ${SERVICE_NAME};
        (if [ $type != prod ];then for dir in ${SERVICE_NAME}-*; do if [ \$dir != $TAR_NAME ]; then rm -rf \$dir ; fi; done fi)
    fi
    if [ "$2"s != "--standby"s ]; then
        #cd ${REMOTE_PATH}/${SERVICE_NAME}/docker; sudo docker-compose up -d --build
        sudo bash -x $SERVICE_NAME/docker/$START_FILE $BUILD_HALL_LIST \$PUBLISH_ALL;
    fi;
    ")
done