FROM golang:1.19.2-alpine AS build
WORKDIR /build
ADD go.* ./
RUN go mod download
COPY . ./
RUN CGO_ENABLED=0 GOOS=linux go build -a -o showme .

FROM alpine:latest
WORKDIR /root
COPY --from=build /build/showme ./
ADD views views
ADD assets assets
CMD ./showme