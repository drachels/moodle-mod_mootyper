#!/usr/bin/env bash
set -euo pipefail

PID_FILE="/tmp/mootyper-geckodriver.pid"
LOG_FILE="/tmp/mootyper-geckodriver.log"
PORT="${GECKODRIVER_PORT:-4444}"

is_running() {
    [[ -f "$PID_FILE" ]] && kill -0 "$(cat "$PID_FILE")" 2>/dev/null
}

start_driver() {
    if is_running; then
        echo "geckodriver already running on port $PORT (pid $(cat "$PID_FILE"))."
        return 0
    fi

    nohup geckodriver --port "$PORT" >"$LOG_FILE" 2>&1 &
    echo $! > "$PID_FILE"

    for _ in $(seq 1 20); do
        if curl -fsS "http://127.0.0.1:${PORT}/status" >/dev/null 2>&1; then
            echo "geckodriver started on port $PORT (pid $(cat "$PID_FILE"))."
            return 0
        fi
        sleep 1
    done

    echo "Failed to start geckodriver. Check $LOG_FILE" >&2
    exit 1
}

stop_driver() {
    if is_running; then
        kill "$(cat "$PID_FILE")" >/dev/null 2>&1 || true
        rm -f "$PID_FILE"
        echo "geckodriver stopped."
        return 0
    fi

    pkill -f "geckodriver --port ${PORT}" >/dev/null 2>&1 || true
    rm -f "$PID_FILE"
    echo "geckodriver not running."
}

status_driver() {
    if is_running; then
        echo "running (pid $(cat "$PID_FILE"), port $PORT)"
    else
        echo "stopped"
    fi
}

case "${1:-}" in
    start)
        start_driver
        ;;
    stop)
        stop_driver
        ;;
    restart)
        stop_driver
        start_driver
        ;;
    status)
        status_driver
        ;;
    *)
        echo "Usage: $0 {start|stop|restart|status}" >&2
        exit 1
        ;;
esac
