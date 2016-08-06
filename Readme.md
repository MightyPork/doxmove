DoxMove
=======

Run the php script in terminal.

Usage
-----

Args: 

- C file
- H file
- action (optional: -w ... write result into the H file, -d ... print result)

Example usage
-------------

```
~/.../Drivers/STM32F1xx_HAL_Driver $ doxmove Src/stm32f1xx_hal_flash_ex.c Inc/stm32f1xx_hal_flash_ex.h
[SUC] HAL_StatusTypeDef HAL_FLASHEx_Erase()
[SUC] HAL_StatusTypeDef HAL_FLASHEx_Erase_IT()
[SUC] HAL_StatusTypeDef HAL_FLASHEx_OBErase()
[SUC] HAL_StatusTypeDef HAL_FLASHEx_OBProgram()
[SUC] void HAL_FLASHEx_OBGetConfig()
[SUC] uint32_t HAL_FLASHEx_OBGetUserData()
[ - ] void FLASH_PageErase()
```

It prints 'SUC' for matched dox comment, 'alr' for already copied comment detected, and ' - ' if func found only in C, but not H.
