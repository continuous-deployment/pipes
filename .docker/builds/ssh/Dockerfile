FROM alpine:latest

MAINTAINER Daniel Atkinson <hourd.tasa@gmail.com>

RUN apk update && \
    apk add bash git openssh rsync && \
    mkdir -p ~root/.ssh && chmod 700 ~root/.ssh/ && \
    echo -e "Port 22\n" >> /etc/ssh/sshd_config && \
    cp -a /etc/ssh /etc/ssh.cache && \
    rm -rf /var/cache/apk/* && \
    adduser -S -D knockknock -s /bin/bash && \
    echo knockknock:letmein | chpasswd

EXPOSE 22

COPY entry.sh /entry.sh
COPY ssh_keys/ /root/.ssh/
COPY ssh_keys/id_rsa.pub /root/.ssh/authorized_keys

ENTRYPOINT ["/entry.sh"]

CMD ["/usr/sbin/sshd", "-D", "-f", "/etc/ssh/sshd_config"]
