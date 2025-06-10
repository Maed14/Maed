// Eng Wei Jiun

// This class is use to play the sound effect function.
package Controller;

import javax.sound.sampled.*;
import java.io.*;

public class SoundListener {
    private Clip soundEffectClip;
    private String soundFilePath;

    public SoundListener() {
        this.soundFilePath = "material/voice/SoundEffect.wav";
        this.soundEffectClip = loadAudio(soundFilePath);
    }

    // Load the audio file from the file path
    private Clip loadAudio(String filePath) {
        try {
            File audioFile = new File(filePath);
            AudioInputStream audioStream = AudioSystem.getAudioInputStream(audioFile);
            Clip clip = AudioSystem.getClip();
            clip.open(audioStream);
            return clip;
        } catch (UnsupportedAudioFileException | IOException | LineUnavailableException e) {
            System.err.println("Error loading audio file: " + filePath);
            e.printStackTrace();
            return null;
        }
    }

    // Play the sound effect
    public void playSoundEffect() {
        if (soundEffectClip != null) {
            soundEffectClip.setFramePosition(0);
            soundEffectClip.start();
        }
    }
}
