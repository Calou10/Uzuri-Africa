# Use the official Nginx image as the base image
FROM nginx:latest

# Set the working directory
WORKDIR /usr/share/nginx/html

# Copy your HTML files into the container
COPY . /usr/share/nginx/html

# Expose port 80 to access the server
EXPOSE 80

# Start Nginx server
CMD ["nginx", "-g", "daemon off;"]
