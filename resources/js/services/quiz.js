import axios from 'axios';

const API_URL = 'http://127.0.0.1:8000/api/';

class QuizService {
    setupInterceptors() {
        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response && error.response.status === 403) {
                    alert('You do not have permission to do this action.');
                }
                return Promise.reject(error);
            }
        );
    }
    async fetchQuizzes() {
        const token = localStorage.getItem('token'); // Get the token from local storage
        return await axios.get(`${API_URL}quizzes`, {
            headers: {
                Authorization: `Bearer ${token}` // Include the token in the request headers
            }
        });
    }

    async submitQuiz(questions, userAnswers) {
        const token = localStorage.getItem('token'); // Get the token from local storage
        return await axios.post(`${API_URL}quizzes/submit`, {
            questions,
            answers: userAnswers
        }, {
            headers: {
                Authorization: `Bearer ${token}` // Include the token in the request headers
            }
        });
    }
}

export default new QuizService();
