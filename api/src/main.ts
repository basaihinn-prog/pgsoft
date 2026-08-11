import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';

async function bootstrap() {
  const app = await NestFactory.create(AppModule);
  app.enableCors({
    origin: process.env.PANEL_ORIGIN ?? 'https://panel.pgplay.online',
    credentials: true,
  });
  await app.listen(process.env.PORT ?? 3000);
}

bootstrap();
