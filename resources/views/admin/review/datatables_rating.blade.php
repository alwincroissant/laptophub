<span style="color:#b8860b">{{ str_repeat('★', (int) $review->rating) . str_repeat('☆', 5 - (int) $review->rating) }}</span>
