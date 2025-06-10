// Eng Wei Jiun

// This class is use to play the background music function.
package Controller;

import javax.sound.sampled.*;
import java.io.*;

public class MusicListener {
    private Clip backgroundMusicClip;
    private String musicFilePath;
    public MusicListener() {
        this.musicFilePath = "material/voice/BackgroundMusic.wav";
        this.backgroundMusicClip = loadAudio(musicFilePath);

        // Loop the background music
        if (backgroundMusicClip != null) {
            backgroundMusicClip.loop(Clip.LOOP_CONTINUOUSLY);
        }
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

    // Play the background music
    public void playMusic() {
        if (backgroundMusicClip != null) {
            backgroundMusicClip.loop(Clip.LOOP_CONTINUOUSLY);
        }
    }

    // Stop the background music
    public void stopMusic() {
        if (backgroundMusicClip != null) {
            backgroundMusicClip.stop();
        }
    }

}
