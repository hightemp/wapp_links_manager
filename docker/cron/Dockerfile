# Dockerfile to create image with cron services
FROM ubuntu:latest
MAINTAINER hightemp

#Install Cron
RUN apt-get update
RUN apt-get -y install cron curl

# Add the cron job
# RUN crontab -l | { cat; echo "* * * * * bash /root/get_date.sh"; } | crontab -

# Run the command on container startup
CMD cron