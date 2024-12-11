# Use the official Nginx image from Docker Hub
FROM nginx:alpine

# Set the working directory to /usr/share/nginx/html
WORKDIR /usr/share/nginx/html

# Copy your HTML files to the container's working directory
COPY . .

# Expose port 80 for the web server
EXPOSE 80

# The default command runs Nginx
CMD ["nginx", "-g", "daemon off;"]

