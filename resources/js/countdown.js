export default function countdown(targetDate) {
  return {
    target: new Date(targetDate),
    days: 0,
    hours: 0,
    minutes: 0,
    seconds: 0,
    interval: null,
    updateCountdown() {
      const now = new Date();
      const difference = this.target - now;

      if (difference <= 0) {
        clearInterval(this.interval);
        this.days = this.hours = this.minutes = this.seconds = 0;
        return;
      }

      this.days = Math.floor(difference / (1000 * 60 * 60 * 24));
      this.hours = Math.floor((difference / (1000 * 60 * 60)) % 24);
      this.minutes = Math.floor((difference / (1000 * 60)) % 60);
      this.seconds = Math.floor((difference / 1000) % 60);
    },
    init() {
      this.updateCountdown();
      this.interval = setInterval(() => this.updateCountdown(), 1000);
    },
  };
}
