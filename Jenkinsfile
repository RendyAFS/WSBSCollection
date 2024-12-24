pipeline {
    agent any
    environment {
        DOCKER_HUB_REPO = 'rendyafs/wsbs_collection' // DockerHub username Anda
        APP_NAME = 'wsbs_collection'
        DOCKER_SERVER = 'localhost' // Docker Desktop berjalan di lokal
    }
    stages {
        // Stage 1: Checkout kode dari GitHub
        stage("Checkout Code") {
            steps {
                echo "Checking out project from GitHub..."
                git branch: 'Develope', url: 'https://github.com/RendyAFS/WSBSCollection.git'
            }
        }

        // Stage 2: Build Docker image
        stage("Build Docker Image") {
            steps {
                echo "Building Docker image for ${APP_NAME}..."
                sh "docker build -t ${APP_NAME}:latest ."
            }
        }

        // Stage 3: Push Docker image ke DockerHub
        stage("Push Docker Image to DockerHub") {
            steps {
                echo "Tagging and pushing Docker image to DockerHub..."
                sh "docker tag ${APP_NAME}:latest ${DOCKER_HUB_REPO}:latest"
                sh "docker push ${DOCKER_HUB_REPO}:latest"
            }
        }

        // Stage 4: Deploy using Docker Compose
        stage("Deploy Application with Docker Compose") {
            steps {
                echo "Deploying application using Docker Compose..."
                sh "docker-compose down" // Hentikan container jika sudah berjalan
                sh "docker-compose up -d" // Jalankan container secara daemon
            }
        }

        // Stage 5: Health Check
        stage("Health Check") {
            steps {
                echo "Checking if application is running..."
                sh "curl -f http://localhost:8081 || exit 1" // Periksa aplikasi melalui Nginx
            }
        }
    }

    post {
        always {
            echo "Pipeline execution complete."
        }
        success {
            echo "Pipeline executed successfully."
        }
        failure {
            echo "Pipeline execution failed."
        }
    }
}
