#!/bin/bash
export SITE_ROOT=`pwd`
httpd -DUser=$USER -DGroup=staff -DNO_DETACH -DFOREGROUND -f ${SITE_ROOT}/config/httpd_local.conf -d ${SITE_ROOT}
